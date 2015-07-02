<div class="col-sm-12">
    <div class="well bs-component">
        <h2>Einstellungen</h2>
        <br/>
        <?php if ($success) { ?>
            <div class="alert alert-dismissable alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                Einstellungen erfolgreich gesichert.
            </div>
            <br/>
        <?php } elseif ($error) { ?>
            <div class="alert alert-dismissable alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                Leider konnten deine Einstellungen nicht gespeichert werden. Bitte versuchen Sie es nocheinmal und
                achten
                Sie darauf eine korrekte Mail Adresse anzugeben und eine korrekte Passwort Wiederholung. Sollte das
                Problem wiederholt auftreten wenden schreiben Sie uns eine Nachricht.
            </div>
            <br/>
        <?php } ?>

        <form action="<?php echo $bp ?>settings/update" method="post" class="form-horizontal" role="form">

            Wird die E-Mail Adresse geändert kann Ihnen bei Vergessen des Passworts unter Umständen kein Passwort mehr
            zugesendet werden! Bitte beachten Sie dass das Passwort zum Login benötigt wird.

            <div class="form-group">
                <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                <div class="col-sm-10">
                    <input type="email" name="mail" class="form-control" id="inputEmail"
                           placeholder="<?php echo $mail; ?>">
                </div>
            </div>
            <br/>

            Das Passwortfeld kann leer gelassen werden. Das Passwort wird nur geändert wenn das Passswortfeld den
            <abbr title="mindestens 6 Zeichen Länge">Passwortkriterien</abbr> entspricht und mit der Wiederholung
            übereinstimmt.
            <br/>

            <div class="form-group">
                <label for="inputPassword" class="col-sm-2 control-label">Passwort</label>

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
                    Speichern!
                </button>
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
</div>

<script src="<?php echo $bp; ?>lib/js/datastore-credentials.js"></script>