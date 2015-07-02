<div class="col-sm-3"></div>
<div class="col-sm-6">
    <div class="well bs-component">


        <form action="<?php echo $bp; ?>login/checkCredentials" method="post" class="form-horizontal" role="form">
            <fieldset>
                <legend>Login</legend>

                <?php if ($loginFailed) { ?>
                    Leider gab es ein Problem. Für weitere Informationen klicken Sie <a data-toggle="modal"
                                                                                        data-target="#complete-dialog">hier</a>.
                    <div id="complete-dialog" class="modal fade" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                                    </button>
                                    <h4 class="modal-title">Benutzernamen oder Passwort vergessen?</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Überprüfen Sie ob Sie die richtige Mail Adresse angegeben haben.
                                        Prüfen Sie ob sie Ihr korrektes Passwort angegeben haben.
                                    </p>
                                    <br/>
                                    <h4 class="modal-title">Account verifiziert?</h4>
                                    <br/>

                                    <p>Haben Sie Ihren Account bereits verifiziert?
                                        Wenn nicht klicken Sie auf den Link in der E-Mail die wir Ihnen bei der
                                        Registrierung zugesandt haben.</p>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" data-dismiss="modal">Schließen</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <br/>
                <?php } ?>

                <div class="form-group">
                    <label for="inputMail" class="col-md-2 control-label">Mail:</label>

                    <div class="col-md-10">
                        <input type="text" name="inputMail" class="form-control" id="inputMail"
                               placeholder="mail@example.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword" class="col-md-2 control-label">Passwort:</label>

                    <div class="col-md-10">
                        <input type="password" name="inputPassword" class="form-control" id="inputPassword"
                               placeholder="super geheim">
                    </div>
                </div>


                <div class="col-md-offset-2">
                    <button class="btn btn-primary" type="submit">Login</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<div class="col-sm-3"></div>