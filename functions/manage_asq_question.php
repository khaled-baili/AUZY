<?php
function manage_question()
{
    if (isset($_POST['submit'])) {
        if ($_POST['category']) {
            global $wpdb;
            try {
                $table_name = $wpdb->prefix . 'question_category';
                $category = $_POST['category'];
                $results = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE name = " . $category . " ");
                if ((count((array)$results)) == 0) {
                    $sql = $wpdb->prepare("INSERT INTO " . $table_name . " (name) VALUES ( %s ) ", $category);
                    $wpdb->query($sql);
                    echo '<script>confirm("Question Saved Successfully")</script>';
                } else {
                    echo '<script>alert("Question exist!!!")</script>';
                }
            } catch (Exception $e) {
                echo $e;
            }
        } else {
            echo '<script>confirm("Data doesn t saved")</script>';
        }
    }

?>
    <div class="container">
        <div class="row table-title">
            <div class="col-sm-6">
                <h2>Manage Category</h2>
            </div>
            <div class="col-sm-6">
                <div class="functional-btn">
                    <button type="button" name="add_categ" id="add_categ" class="btn btn-info">Add category</button>
                </div>
            </div>
        </div>
        <div class="row">
            <table id="category_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="row table-title">
            <div class="col-sm-6">
                <h2>Questions</h2>
            </div>
            <div class="col-sm-6">
                <div class="functional-btn">
                    <button type="button" name="add" id="add" class="btn btn-info">Add question</button>
                </div>
            </div>
        </div>
        <div class="row">
            <table id="question_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>

    <!--Add category -->


    <script>
        jQuery(document).ready(function($) {
            $('#add').click(function() {
                var html = '<tr>';
                html += '<td contenteditable id="data1"><input type="text" name="question" id="question" class="form-control" placeholder="Please enter your question"></td>';
                html += '<td contenteditable id="data2"><select name="type" id="type" class="form-control" required>' +
                    '<option value="A">ASC</option>' +
                    '<option value="D">DESC</option></select></td > ';
                html += '<td contenteditable id="data3"><select name="category_id" id="category_id" class="form-control" required>' +
                    '<?php
                        $connect = mysqli_connect("localhost", "root", "", "auzy");
                        $query = "SELECT * FROM  wp_question_category";
                        $result = mysqli_query($connect, $query);
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<option value="' . $row["idcateg"] . '">' . $row["_name"] . '</option>';
                        }
                        ?>';
                html += '</select></td><td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs"><i class="far fa-plus-square"></i> &nbsp Insert</button></td>';
                html += '</tr>';
                $('#question_table tbody').prepend(html);
            });

        });
    </script>

<?php

}


?>