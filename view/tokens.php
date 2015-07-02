<div class="col-sm-12">

    <h1>Tokens</h1>

    Hier können Sie Ihre Tokens einsehen, neue hinzufügen und bestehende löschen.<br/>
    Beachten Sie das Mit einem Token auf alle Ihre gespeicherten Daten zugegriffen werden kann.
    <br/>
    <br/>

    <form method="post" action="<?php echo $bp; ?>tokens/add" class="form-horizontal" role="form" id="addTokenForm">

        <table class="table table-striped table-hover ">
            <thead>
            <tr>
                <th>Kommentar</th>
                <th>Token</th>
                <th style="min-width: 150px;">Gültig ab</th>
                <th style="min-width: 150px;">Gültig bis</th>
                <th>lesen</th>
                <th>schreiben</th>
                <th>Token löschen</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($tokens as $token) { ?>
                <tr>
                    <td><?php echo $token['comment']; ?></td>
                    <td style="word-break: break-all;">

                        <i class="fa fa-search" data-toggle="modal"
                           data-target="#complete-dialog-<?php echo substr($token['token'], 8); ?>"></i>

                        <div id="complete-dialog-<?php echo substr($token['token'], 8); ?>" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                                        </button>
                                        <h4 class="modal-title">Token</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            <?php echo $token['token']; ?>
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" data-dismiss="modal">Schließen</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </td>
                    <td><?php echo date('d.m.Y H:i', $token['validFrom']); ?></td>
                    <td><?php echo date('d.m.Y H:i', $token['validTo']); ?></td>
                    <td><?php echo $token['r'] ? "<i class='fa fa-check'></i>" : "<i class='fa fa-ban'></i>"; ?></td>
                    <td><?php echo $token['w'] ? "<i class='fa fa-check'></i>" : "<i class='fa fa-ban'></i>"; ?></i></td>
                    <td><a href="<?php echo $bp; ?>tokens/delete/<?php echo $token['token']; ?>"><i
                                class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td><input name="comment" type="text" class="tokenInput" id="inputComment" placeholder="Kommentar">
                </td>
                <td>
                    <div class="text-muted">-</div>
                </td>
                <td><input name="validFrom" type="text" class="tokenInput" id="inputValidFrom"
                           value="<?php echo date('d.m.Y H:i') ?>">
                </td>
                <td><input name="validTo" type="text" class="tokenInput" id="inputValidUntil"
                           value="<?php echo date('d.m.Y H:i') ?>">
                </td>
                <td>
                    <div style="padding: 0px;" class="checkbox">
                        <label>
                            <input type="checkbox" name="r">
                        </label>
                    </div>
                </td>
                <td>
                    <div style="padding: 0px;" class="checkbox">
                        <label>
                            <input type="checkbox" name="w">
                        </label>
                    </div>
                </td>
                <td>
                    <i class="fa fa-chevron-right" onclick="$('#addTokenForm').submit();"></i>
                </td>
            </tr>

            </tbody>
        </table>
    </form>
</div>
<script type="text/javascript">

    var options = {
        lang: 'de',
        i18n: {
            de: {
                months: [
                    'Januar', 'Februar', 'März', 'April',
                    'Mai', 'Juni', 'Juli', 'August',
                    'September', 'Oktober', 'November', 'Dezember',
                ],
                dayOfWeek: [
                    "So.", "Mo", "Di", "Mi",
                    "Do", "Fr", "Sa.",
                ]
            }
        },
        format: 'd.m.Y H:i',
        minDate: '-0',
        maxDate: '+1971/01/02'

    };

    $('#inputValidFrom').datetimepicker(options);
    $('#inputValidUntil').datetimepicker(options);
</script>