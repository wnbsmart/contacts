<?php

namespace App\Http\Controllers;

use App\Contact;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('contacts.index');
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function show($id)
    {
        Contact::findOrFail($id);
        return view('contacts.show', compact('id'));
    }

    public function showFavourites()
    {
        return view('contacts.show_favourites');
    }

    public function edit($id)
    {
        Contact::findOrFail($id);
        return view('contacts.edit', compact('id'));
    }
}
