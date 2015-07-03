<div class="col-sm-12">
    <h1>View Stream List</h1>

    &nbsp;
    <div class="bs-callout bs-callout-danger">

        <strong class="text-danger">Unter Construction</strong>

    </div>


    <br/>
    <hr/>
</div>


<div class="col-sm-12">
    <h3>Streams:</h3>

    <form method="post" action="<?php echo $bp; ?>data/add" class="form-horizontal" role="form" id="addTokenForm">


        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>StreamId</th>
                <th>Kommentar</th>
                <th>viewStream</th>
                <th>viewData</th>
                <th><i id="enableSaveDelete" class="fa fa-toggle-off"></i></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($streams as $stream) {
                ?>
                <tr>
                    <td><?php echo $stream['sid']; ?></td>
                    <td><?php echo $stream['comment']; ?></td>
                    <td><a href="<?php echo $bp; ?>data/stream/<?php echo $stream['sid']; ?>">viewStream</a></td>
                    <td><a href="<?php echo $bp; ?>data/view/<?php echo $stream['sid']; ?>">viewData</a></td>
                    <td><a class="saveDelete" href="<?php echo $bp; ?>data/delete/<?php echo $stream['sid']; ?>"><i
                                class="fa fa-trash"></i></a>
                </tr>
            <?php } ?>
            <tr>
                <td>
                    <div class="text-muted">-</div>
                </td>
                <td><input name="comment" type="text" class="tokenInput" id="inputComment" placeholder="Kommentar">
                </td>

                <td>
                    <div class="text-muted">-</div>
                </td>
                <td>
                    <div class="text-muted">-</div>
                </td>
                <td>
                    <i class="fa fa-chevron-right" onclick="$('#addTokenForm').submit();"></i>
                </td>
            </tr>

            </tbody>
        </table>
    </form>

    <hr/>
</div>

<script>

    $(document).ready(function () {
        $('.saveDelete').addClass("text-muted");
    });

    $("#enableSaveDelete").click(function () {
        if ($(this).hasClass("fa-toggle-off")) {
            $(this).removeClass("fa-toggle-off");
            $(this).addClass("fa-toggle-on");
            $('.saveDelete').removeClass("text-muted");
        } else {
            $(this).addClass("fa-toggle-off");
            $(this).removeClass("fa-toggle-on");
            $('.saveDelete').addClass("text-muted");
        }
    });


    $('.saveDelete').click(function (e) {
        if ($("#enableSaveDelete").hasClass("fa-toggle-off")) {
            e.preventDefault();
        }
        //do other stuff when a click happens
    });
</script>