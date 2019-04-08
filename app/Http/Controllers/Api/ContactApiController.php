<?php

namespace App\Http\Controllers\Api;

use App\Contact;
use App\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ContactApiController
{
    public function index(Request $request)
    {
        return $request->user()->contacts;
    }

    public function show(Request $request, $id)
    {
        $contact = Contact::where('user_id', $request->user()->id)->where('id', $id)->first()->load('phones');

        return $contact;
    }

    public function showFavourites(Request $request)
    {
        $favourites = Contact::where('user_id', $request->user()->id)->where('favourite', 1)->get();

        return $favourites;
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email',
            'number' => 'required',
            'label' => 'required',
            'contact_img' => 'nullable|mimes:jpg,jpeg,png',
        ]);

        if($validation->passes()) {
            if ($request->hasFile('contact_img')) {
                $image = $request->file('contact_img');
                $extension = $image->getClientOriginalExtension();
                $imageName = uniqid() . '.' . $extension;
                $image->move(public_path() . '/img/', $imageName);
            }

            $contact = Contact::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'image' => $imageName ?? null,
                'favourite' => isset($request->favourite) ? 1 : 0,
            ]);

            $contact->phones()->create([
                'number' => $request->number,
                'label' => $request->label,
            ]);

            return response()->json([
                'message'   => 'New contact added successfully',
                'messageCss' => 'display',
                'messageCss2' => 'block',
                'class_name'  => 'alert-success'
            ]);
        }
        else{
            return response()->json([
                'message' => $validation->errors()->all(),
                'messageCss' => 'display',
                'messageCss2' => 'block',
                'class_name'  => 'alert-danger'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        if ($request->user()->id !== $contact->user_id) {
            return response()->json(['error' => 'You can only edit your own contacts.'], 403);
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email',
            'contact_img' => 'nullable|mimes:jpg,jpeg,png',
        ]);

        if($validation->passes()) {

            if ($request->hasFile('contact_img')) {
                $imageName = $this->uploadImageAndGetName($request->file('contact_img'));
                $this->destroyImage($contact->image);
            }

            // update contact
            $contact->update([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'favourite' => isset($request->favourite) ? 1 : 0,
                'image' => $imageName ?? $contact->image,
            ]);

            // update contact's phones
            foreach ($contact->phones as $key => $value) {
                $value->update([
                    'label' => $request->label[$key],
                    'number' => $request->number[$key],
                ]);
            }

            return response()->json([
                'message' => 'Contact updated successfully',
                'messageCss' => 'display',
                'messageCss2' => 'block',
                'class_name' => 'alert-success'
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);

        if ($request->user()->id !== $contact->user_id) {
            return response()->json(['error' => 'You can only delete your own contacts.'], 403);
        }

        $this->destroyImage($contact->image);
        $contact->delete();

        return response()->json([
            'message' => 'Contact deleted successfully',
            'messageCss' => 'display',
            'messageCss2' => 'block',
            'class_name'  => 'alert-success'
        ], 204);
    }

    private function uploadImageAndGetName($image)
    {
        $extension = $image->getClientOriginalExtension();
        $imageName = uniqid() . '.' . $extension;
        $image->move(public_path() . '/img/', $imageName);

        return $imageName;
    }

    private function destroyImage($image)
    {
        if(File::exists(public_path()."/img/".$image)) {
            File::delete(public_path()."/img/".$image);
        }
    }

    public function searchAllContacts($input)
    {
        if($input === null || $input === "" || $input === "all") {
            return Contact::where('user_id', Auth::id())->get();
        }

        return Contact::where('user_id', Auth::id())
                        ->where(function ($query) use ($input){
                            $query->where('name', 'LIKE', '%'.$input.'%')
                                ->orWhere('surname', 'LIKE', '%'.$input.'%')
                                ->orWhere('email', 'LIKE', '%'.$input.'%');
                        })
                        ->get();
    }

    public function searchFavouriteContacts($input)
    {
        if($input === null || $input === "" || $input === "all") {
            return Contact::where('user_id', Auth::id())->where('favourite', 1)->get();
        }

        return Contact::where('user_id', Auth::id())
                        ->where('favourite', 1)
                        ->where(function ($query) use ($input){
                            $query->where('name', 'LIKE', '%'.$input.'%')
                                ->orWhere('surname', 'LIKE', '%'.$input.'%')
                                ->orWhere('email', 'LIKE', '%'.$input.'%');
                        })
                        ->get();
    }
}
