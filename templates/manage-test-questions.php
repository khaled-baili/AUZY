<div class="container">
    <?php require_once WP_PLUGIN_DIR.'/auzy-tests/frontend.php';
    echo require_once WP_PLUGIN_DIR.'/auzy-tests/frontend.php';
    $frontend->show_all_questions();
    ?>
</div>







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