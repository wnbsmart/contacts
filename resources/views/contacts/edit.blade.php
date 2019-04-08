@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center">
                    <a href="{{ route('contacts.index') }}">All contacts</a> |
                    <a href="{{ route('contacts.show.favourites') }}">My favourites</a>
                </div>
            </div>
        </div>
        <hr>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert" id="messages" style="display: none"></div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                Edit contact
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('contacts.api.delete', ['id' => $id]) }}" id="form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger float-right"
                                            onclick="return confirm('Are you sure that you want to delete this contact?');">Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="contact" class="row justify-content-center mt-3">
                        <div class="col-md-8">
                            <form id="form-edit" enctype="multipart/form-data" action="{{ route('contacts.api.update', ['id' => $id]) }}" method="POST">
                                @csrf
                                @method("PUT")
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            // DELETE CONTACT
            $('#form-delete').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "DELETE",
                    url: $(this).attr('action'),
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        window.location.href = '{{ route('contacts.index') }}';
                    },
                    error: function (data) {
                        console.log('An error occurred.');
                        console.log(data);
                    }
                });
            });

            // UPDATE CONTACT
            $('#form-edit').submit( function( e ) {
                e.preventDefault();
                var msgBox = $('#messages');
                var form = new FormData(this);
                form.append('_method', 'PUT');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('contacts.api.update', ['id' => $id]) }}',
                    type: "POST",
                    data: form,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        msgBox.addClass(data.class_name).removeClass('alert-danger');
                        msgBox.css(data.messageCss, data.messageCss2);
                        msgBox.html(data.message);
                        msgBox.addClass(data.class_name);
                    },
                    error: function (data) {
                        console.log('An error occurred.');
                        console.log(data);
                        msgBox.css(data.messageCss, data.messageCss2);
                        msgBox.html(data.message);
                        msgBox.addClass(data.class_name).removeClass('alert-success');
                    }
                });
            });

            // SHOW EDITABLE CONTACT
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '{{ route('contacts.api.show', ['id' => $id]) }}',
                success: function(result){

                    var checked = '';
                    if(result.favourite === 1){
                        checked = 'checked';
                    }
                    var template =
                                '<div class="form-group">' +
                                    '<label for="name">Name</label>' +
                                    '<input type="text" class="form-control" id="name" name="name" value="$name">' +
                                '</div>'+
                                '<div class="form-group">' +
                                    '<label for="surname">Surname</label>' +
                                    '<input type="text" class="form-control" id="surname" name="surname" value="$surname">' +
                                '</div>'+
                                '<div class="form-group">' +
                                    '<label for="email">Email</label>' +
                                    '<input type="email" class="form-control" id="email" name="email" value="$email">' +
                                '</div>'+
                                '<div class="form-group">' +
                                    '<label for="contact_img">Image</label><br>' +
                                    '<input type="file" id="contact_img" name="contact_img">' +
                                '</div>'+
                                '<div class="form-group">' +
                                    '<label for="favourite">' +
                                        '<input type="checkbox" id="favourite" name="favourite" checked="$favourite"> Favourite' +
                                    '</label>' +
                                '</div>'+
                                '<hr>' +
                                '<div id="phones" class="row"></div>' +
                                '<button type="submit" class="btn btn-primary mt-3">Update</button>';

                    var templ = template.replace("$id", result.id);
                    templ = templ.replace("$name", result.name);
                    templ = templ.replace("$surname", result.surname);
                    templ = templ.replace("$email", result.email);
                    templ = templ.replace("$image", result.image);
                    templ = templ.replace("$favourite", checked);

                    $('#form-edit').append(templ);

                    var phoneTempl =
                        '<div class="col-md-6 mt-2">' +
                            '<label for="label">Label</label>' +
                            '<input type="text" class="form-control" id="label" name="label[$element]" value="$label">' +
                        '</div>' +
                        '<div class="col-md-6 mt-2">' +
                            '<label for="number">Number</label>' +
                            '<input type="text" class="form-control" id="number" name="number[$element]" value="$number">' +
                        '</div>';

                    $(result.phones).each(function(index, value) {
                        var templ = phoneTempl.replace("$number", value.number);
                        templ = templ.replace("$label", value.label);
                        templ = templ.replace("$element", index);
                        templ = templ.replace("$element", index);

                        $('#phones').append(templ);
                    });
                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                }
            });
        });
    </script>
@endsection
