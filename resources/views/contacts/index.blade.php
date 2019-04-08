@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="text-center">
                    <a href="{{ route('contacts.index') }}">All contacts</a> |
                    <a href="{{ route('contacts.show.favourites') }}">My favourites</a>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="alert" id="messages" style="display: none;"></div>
            </div>
        </div>
        <div class="row ">
            <div class="col-md-2">
                <a href="{{ route('contacts.create') }}" class="btn btn-primary">Add new contact</a>
            </div>
            <div class="col-md-8">
                <input type="text" id="searchInput" placeholder="Search..." class="form-control">
            </div>
        </div>
        <div id="contacts" class="row"></div>
    </div>
@endsection

@section('scripts')
    <script>
        var APP_URL = '{{ config('app.url') }}';

        $(document).ready(function() {

            // INDEX:
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('contacts.api.index') }}',
                success: function(result){
                    displayContacts(result);
                }
            });

            $("#searchInput").on("keyup", function() {
                var input = $(this).val().toLowerCase();
                if(input === "") input = "all";

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: 'api/contacts/search/all/' + input,
                    type: 'GET',
                    success: function(result){
                        $('.contact').remove();
                        displayContacts(result);

                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });
        });

        function displayContacts(result){
            var template =
                '<a class="custom-text" href="{{ url('contacts/$id') }}">' +
                    '<div class="col-md-3 mt-5 contact">' +
                        '<div class="card" style="width: 18rem;">' +
                            '<img class="card-img-top" src="{{ config('app.url') }}img/$image">' +
                            '<div class="card-body">' +
                                '<p>Name: $name $surname</p>' +
                                '<input type="checkbox" $favourite disabled> Favourite<br>' +
                                '<form action="{{ config('app.url') }}api/contacts/$id" class="form-delete">' +
                                '@csrf' +
                                '@method("DELETE")'+
                                '<button type="submit" class="btn btn-danger float-right">Delete</button>' +
                                '</form>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</a>';
            $(result).each(function(index, value){
                var checked = '';
                if(value.favourite === 1){
                    checked = 'checked';
                }
                var templ = template.replace("$id", value.id);
                templ = templ.replace("$id", value.id);
                templ = templ.replace("$name", value.name);
                templ = templ.replace("$surname", value.surname);
                templ = templ.replace("$image", value.image);
                templ = templ.replace("$favourite", checked);

                $('#contacts').append(templ);
            });
        }
    </script>

    <script src="{{ asset('js/delete-contact.js') }}"></script>

@endsection
