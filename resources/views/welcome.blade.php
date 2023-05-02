<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"
        integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>User Details</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>

<body>
    <div class="container pt-3">
        <h2>User Table</h2>
        <table class="table table-bordered mt-3">
            <thead class="bg-dark text-white">
                <th>Sr No.</th>
                <th>Name</th>
                <th>UserName</th>
                <th>Email</th>
                <th>Action</th>
            </thead>
            <tbody id="list_todo">
                @foreach ($users as $user)
                    <tr>

                        <td width="20">{{ $user->id }}</td>
                        <input type="hidden" value="{{ $user->id }}" name="user_id" id="user_id">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td><button class="btn btn-primary add_address" value="{{ $user->id }}">Add Address</button>
                        </td>
                    </tr>
                @endforeach
        </table>
        <br>
        {{ $users->links() }}
    </div>
    <div class="modal" id="modal_address">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="form_address">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal_title"></h4>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <label>Addres Line 1</label>
                        <input type="text" name="address_line_1" id="address_line_1" class="form-control"
                            placeholder="Enter Adrress Line 1">
                        <br>
                        <label>category</label>
                        <input type="text" name="address_line_2" id="address_line_2" class="form-control"
                            placeholder="Enter Address line 2">
                        <br>
                        <label for="country">Choose a Country:</label>
                        <select name="country" id="country">
                        </select>
                        <button class="address btn btn-success">Add Address</button>

                    </div>
                    <!-- Modal footer -->

                </form>
                <div>
                    <table>
                        <tbody id="address_list">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger close" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        var Accountant = function() {
            var initAccountantValidation = function() {
                $("#form_address").validate({
                    rules: {
                        address_line_1: {
                            required: true,

                        },
                        address_line_2: {
                            required: true,

                        },
                    },
                    messages: {
                        address_line_1: {
                            required: "Address line 1 is  is required.",
                        },
                        address_line_2: {
                            required: "Address line 2 is  is required.",
                        },
                    }
                });
            };
            return {
                init: function() {
                    initAccountantValidation();
                }
            };
        }();
        jQuery(document).ready(function() {
            Accountant.init();
        });
        $(".add_address").on('click', function() {
            var id = $(this).val();
            console.log(id);
            $("#id").val(id);
            $("#form_address").trigger('reset');
            $("#modal_address").modal('show');
            $.ajax({
                type: "GET",
                url: "country",
                dataType: "json",
                success: function(res) {
                    $.each(res, function(index, value) {
                        $('#country').append($('<option>', {
                            value: value,
                            text: value
                        }));
                    });
                }
            });
            $.ajax({
                type: "GET",
                url: "show/" + id,
                dataType: "json",
                success: function(res, status) {
                    $('#address_list').empty();
                    res.forEach(function(ress) {
                        var row = '<tr id="row_todo_' + ress.id + '">';
                        row += '<td width="20">' + ress.id + '</td>';
                        row += '<td width="20">' + ress.address_line_1 + '</td>';
                        row += '<td width="20">' + ress.address_line_2 + '</td>';
                        row += '<td width="20">' + ress.country + '</td>';
                        row += `<td width="20"><button value="` + ress.id +
                            `"  class="btn btn-danger" id="delete_address">Delete</a></td>`;

                        $("#address_list").prepend(row);
                    });

                }
            });
        });
        $(".close").on('click', function() {
            $("#modal_address").modal('hide');
        });
        $("#form_address").submit(function(e) {

            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var user_id = $('#id').val();
            var form_data = new FormData(document.getElementById("form_address"));

            e.preventDefault();

            var form = $(this);
            var actionUrl = form.attr('action');

            if ($('#address_list tr').length < 5) {
                $.ajax({
                    type: "POST",
                    url: "address/store",
                    data: form.serialize(),
                    dataType: "json",
                    success: function(res, status, textStatus) {
                        if (textStatus.responseJSON.status == 400) {

                        } else {
                            var row = '<tr id="row_todo_' + res.id + '">';
                            row += '<td width="20">' + res.id + '</td>';
                            row += '<td width="20">' + res.address_line_1 + '</td>';
                            row += '<td>' + res.address_line_2 + '</td>';
                            row += '<td>' + res.country + '</td>';
                            row += `<td><button value="` + res.id +
                                `"  class="btn btn-danger" id="delete_address">Delete</a></td>`;

                            $("#address_list").prepend(row);

                            $("#form_address").trigger('reset');
                        }
                    }
                });
            } else {
                alert("You can not add more than 5 Addresses");
            }
        });
        $("body").on('click', '#delete_address', function() {
            var id = $(this).val();
            var result = confirm('Are you sure want to delete !');
            if (result) {
                $.ajaxSetup({
                    headers: {
                        'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'DELETE',
                    url: "delete/" + id,
                }).done(function(res) {
                    $("#row_todo_" + id).remove();
                });

            } else {

            }
        });
    </script>

</body>

</html>
