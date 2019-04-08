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
                <h3>Create new contact</h3>
                <form action="{{ route('contacts.api.store') }}" method="POST" id="form-create" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="asd">
                    </div>
                    <div class="form-group">
                        <label for="surname">Surname</label>
                        <input type="text" class="form-control" id="surname" name="surname" placeholder="Surname" value="asd">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="asd@gmail.com">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="label">Label</label>
                                <input type="text" class="form-control" id="label" name="label" placeholder="Label" value="asd">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="number">Number</label>
                                <input type="text" class="form-control" id="number" name="number" placeholder="Number" value="123">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact_img">Image</label><br>
                        <input type="file" id="contact_img" name="contact_img">
                    </div>
                    <div class="form-group">
                        <label for="favourite">
                            <input type="checkbox" id="favourite" name="favourite"> Favourite
                        </label>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('contacts.index') }}" class="btn btn-link">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        $(document).ready(function() {
            $('#form-create').submit(function(e) {
                e.preventDefault();

                var msgBox = $('#messages');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: $(this).attr('action'),
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        // alert(data);
                        msgBox.addClass(data.class_name).removeClass('alert-danger');
                        $('#form-create')[0].reset();
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
        });
    </script>

@endsection
