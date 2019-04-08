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
                <div class="card">
                    <div class="card-header">
                        Contact details
                        <a href="{{ route('contacts.edit', ['id' => $id]) }}" class="btn btn-primary float-right">Edit</a>
                    </div>

                    <div id="contact" class="row mt-3">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('contacts.api.show', ['id' => $id]) }}',
                success: function(result){

                    var checked = '';
                    if(result.favourite === 1){
                        checked = 'checked';
                    }
                    var template =
                        '<div class="col-md-6">' +
                            '<img class="img-fluid" src="{{ config('app.url') }}img/$image">' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<p>Name: $name</p>' +
                            '<p>Surname: $surname</p>' +
                            '<p>Email:  $email</p>' +
                            '<input type="checkbox" $favourite disabled> Favourite' +
                            '<hr>' +
                            '<div id="phones">' +
                                '<h6>Phones (label / number):</h6>' +
                            '</div>' +
                        '</div>';

                    var templ = template.replace("$name", result.name);
                    templ = templ.replace("$surname", result.surname);
                    templ = templ.replace("$email", result.email);
                    templ = templ.replace("$image", result.image);
                    templ = templ.replace("$favourite", checked);

                    $('#contact').append(templ);

                    var phoneTempl = '<p>$label: $number</p>';

                    $(result.phones).each(function(index, value) {
                        var templ = phoneTempl.replace("$number", value.number);
                        templ = templ.replace("$label", value.label);

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
