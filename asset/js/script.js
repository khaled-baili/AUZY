jQuery(document).ready(function($) {
    fetch_data();
    fetch_data_category();

    function fetch_data() {
        var dataTable = $('#question_table').DataTable({
            "processing": true,
            "serverSide": true,
            "dataSrc": "",
            paging: true,
            searching: true,
            stateSave: true,
            "autoWidth": false,
            "columns": [
                { "width": "60%" },
                { "width": "5%" },
                { "width": "5%" },
                { "width": "5%" },
                { "width": "12%" },
            ],
            "columnDefs": [{
                "targets": [1, 4, 2, 3],
                "orderable": false
            }],
            "bDestroy": true,
            "order": [],
            "ajax": {
                url: "/wp-content/plugins/auzy-tests/asset/datatable/question_table.php",
                type: "POST",
                data: { function: "fetch_question" }
            }
        });
    }

    $('#add_question').on('click', function() {
        $('#action').val('addRecord');
        document.getElementById("id").value = '';
        document.getElementById("question").value = '';
        document.getElementById("type").value = '';
        document.getElementById("category").value = '';
        document.getElementById("domaine").value = '';
    });


    $(document).on('click', '#insert', function() {
        var question = document.getElementById('question').value;
        var type = $('#type').val();
        var category_id = $('#category_id').val();
        var domaine_id = $('#domaine_id').val();
        if (question == '') {
            alert("provide a question ");
        } else {
            $.ajax({
                url: "/wp-content/plugins/auzy-tests/asset/php/insert.php",
                method: "POST",
                data: {
                    question: question,
                    domaine_id: domaine_id,
                    type: type,
                    category_id: category_id,
                },
                success: function(data) {
                    $('#alert_message').html('<div class="alert alert-success">' + data + '</div>');
                    $('#question_table').DataTable().destroy();
                    fetch_data();
                }
            });
            setInterval(function() {
                $('#alert_message').html('');
            }, 5000);
        }
    });

    $(document).on('click', '.delete', function() {
        var id = $(this).attr("id");
        if (confirm("Are you sure you want to remove this?")) {
            $.ajax({
                url: "/wp-content/plugins/auzy-tests/asset/datatable/question_table.php",
                method: "POST",
                data: {
                    id: id,
                    function: "delete_question"
                },
                success: function(data) {
                    $('#alert_message').html('<div class="alert alert-success">' + data + '</div>');
                    $('#question_data').DataTable().destroy();
                    fetch_data();
                }
            });
            setInterval(function() {
                $('#alert_message').html('');
            }, 5000);
        }
    });

    //script for category datatable
    function fetch_data_category() {
        var dataTable = $('#category_table').DataTable({
            "processing": true,
            "serverSide": true,
            "dataSrc": "",
            paging: true,
            searching: true,
            stateSave: true,
            "autoWidth": false,
            "columns": [
                { "width": "50%" },
                { "width": "20%" },
                { "width": "10%" },
                { "width": "20%" },
            ],
            "columnDefs": [{
                    "targets": [1, 2, 3],
                    "orderable": false
                }

            ],
            "bDestroy": true,
            "order": [],
            "ajax": {
                url: "/wp-content/plugins/auzy-tests/asset/datatable/categories_table.php",
                type: "POST",
                data: { function: "fetch_categ" }
            }
        });
    }

    $("#category_table").on('click', '.update', function() {
        var idcateg = $(this).attr("id");
        $.ajax({
            url: "/wp-content/plugins/auzy-tests/asset/datatable/categories_table.php",
            method: "POST",
            data: { function: "fetch_categ_by_id", idcateg: idcateg },
            dataType: "json",
            success: function(data) {
                $('#recordModal').modal('show');
                $('#idcateg').val(data.idcateg);
                $('#category_name').val(data._name);
                $('#test_evaluations').val(data.test_eval);
                $('.modal-title').html(" Edit Records");
                $('#action').val('updateRecord');
                $('#save').val('Save');
            }
        });
    });


    // function update_data_category(id, column_name, value) {
    //     $.ajax({
    //         url: "/wp-content/plugins/auzy-tests/asset/php/update_categ.php",
    //         method: "POST",
    //         data: {
    //             id: id,
    //             column_name: column_name,
    //             value: value
    //         },
    //         success: function(data) {
    //             $('#alert_message').html('<div class="alert alert-success">' + data + '</div>');
    //             $('#category_table').DataTable().destroy();
    //             fetch_data_category();
    //         }
    //     });
    //     setInterval(function() {
    //         $('#alert_message').html('');
    //     }, 5000);
    // }
    // $(document).on('blur', '.update_categ', function() {
    //     var id = $(this).data("id");
    //     var column_name = $(this).data("column");
    //     var value = $(this).text();
    //     update_data_category(id, column_name, value);
    // });


    $('#add_categ').click(function() {
        var html = '<tr>';
        html += '<td contenteditable id="data1"><input type="text" name="categ_name" id="categ_name" class="form-control" placeholder="Please enter your category name"></td>';
        html += '<td contenteditable id="data2"><select name="test_eval" id="test_eval" class="form-control" required> ' +
            '<option selected> Choose your test evaluation </option>' +
            '<option value = "AQ" >AQ</option>' +
            '<option value = "Mchat" >Mchat </option>' +
            '</select></td>';
        html += '<td><button type="button" name="insert_categ" id="insert_categ" class="btn btn-success btn-xs"><i class="far fa-plus-square"></i> &nbsp Insert</button></td>';
        html += '</tr>';
        $('#category_table tbody').prepend(html);
    });

    $(document).on('click', '#insert_categ', function() {
        var categ_name = document.getElementById('categ_name').value;
        var test_eval = $('#test_eval').val();
        if (categ_name == '') {
            alert("provide a category name ");
        } else {
            $.ajax({
                url: "/wp-content/plugins/auzy-tests/asset/datatable/categories_table.php",
                method: "POST",
                data: {
                    categ_name: categ_name,
                    test_eval: test_eval,
                    function: 'insert_categ'
                },
                success: function(data) {
                    $('#alert_message').html('<div class="alert alert-success">' + data + '</div>');
                    $('#category_table').DataTable().destroy();
                    fetch_data_category();
                }
            });
            setInterval(function() {
                $('#alert_message').html('');
            }, 5000);
        }
    });

    $(document).on('click', '.delete_categ', function() {
        var id = $(this).attr("id");
        if (confirm("Are you sure you want to remove this?")) {
            $.ajax({
                url: "/wp-content/plugins/auzy-tests/asset/datatable/categories_table.php",
                method: "POST",
                data: {
                    id: id,
                    function: "delete_categ"
                },
                success: function(data) {
                    $('#alert_message').html('<div class="alert alert-success">' + data + '</div>');
                    $('#category_data').DataTable().destroy();
                    fetch_data_category();
                }
            });
            setInterval(function() {
                $('#alert_message').html('');
            }, 5000);
        }
    });

    $('#test-table').DataTable({
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details for ' + data[0] + ' ' + data[1];
                    }
                }),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                    tableClass: 'table'
                })
            }
        }
    });

    $("#question_table").on('click', '.update', function() {
        var id = $(this).attr("id");
        var action = 'getRecord';
        $.ajax({
            url: "/wp-content/plugins/auzy-tests/asset/datatable/question_table.php",
            method: "POST",
            data: { id: id, action: action, function: "fetch_question_by_id" },
            dataType: "json",
            success: function(data) {
                $('#recordModal').modal('show');
                $('#id').val(data.id);
                $('#question').val(data.question);
                $('#type').val(data._type);
                $('#category').val(data.idcateg);
                $('#domaine').val(data._id_domaine);
                $('.modal-title').html("Edit Records");
                $('#action').val('updateRecord');
                $('#save').val('Save');
            }
        });
    });

    function update_data(id, column_name, value) {
        $.ajax({
            url: "/wp-content/plugins/auzy-tests/asset/php/update.php",
            method: "POST",
            data: {
                id: id,
                column_name: column_name,
                value: value
            },
            success: function(data) {
                $('#alert_message').html('<div class="alert alert-success">' + data + '</div>');
                $('#question_table').DataTable().destroy();
                fetch_data();
            }
        });
        setInterval(function() {
            $('#alert_message').html('');
        }, 5000);
    }

    $(document).on('blur', 'update', function() {
        var id = $(this).data("id");
        var column_name = $(this).data("column");
        var value = $(this).text();
        update_data(id, column_name, value);
    });
    $('#survey_table').DataTable({
        pageLength: 10,
        searching: false,
        paging: true,
        "lengthChange": false,
        "ordering": false
    });

});