<div class="col-sm-12">
    <div class="well bs-component">
        <h2>Einstellungen</h2>
        <br/>
        <?php if ($error) { ?>
            <div class="alert alert-dismissable alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                Leider konnten Sie nicht registriert werden. Bitte versuchen Sie es nocheinmal und achten Sie darauf
                eine korrekte Passwort Wiederholung anzugeben. Sollte das Problem wiederholt auftreten wenden schreiben
                Sie uns eine Nachricht.
            </div>
            <br/>
        <?php } ?>

        <form action="<?php echo $bp ?>register/sendIn" method="post" class="form-horizontal" role="form">


            <div class="form-group">
                <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                <div class="col-sm-10">
                    <input type="email" name="mail" class="form-control" id="inputEmail"
                           placeholder="<?php echo $mail; ?>">
                </div>
            </div>
            <br/>

            <div class="form-group">
                <label for="inputPassword" class="col-sm-2 control-label"><abbr title="mindestens 6 Zeichen Länge">Passwort</abbr></label>

                <div class="col-sm-10">
                    <input type="password" name="inputPassword" class="form-control" id="inputPassword">

                    <div class="progress">
                        <div id="passwordStrengthIndicator" class="progress-bar progress-bar-success"
                             style="width: 0%"></div>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label for="inputPassword2" class="col-sm-2 control-label">Passwort wiederholen</label>

                <div class="col-sm-10">
                    <input type="password" name="inputPassword2" class="form-control" id="inputPassword2"
                           placeholder="">
                </div>

            </div>


            <div class="">
                <button id="submitSettings" class="btn btn-primary pull-right" type="submit">
                    Registrieren!
                </button>
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
</div>

<script src="<?php echo $bp; ?>lib/js/datastore-credentials.js"></script>