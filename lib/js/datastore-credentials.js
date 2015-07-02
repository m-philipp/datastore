/**
 * Created by martin on 02.07.2015.
 */


$("#inputPassword2").keyup(checkPasswort);
$("#inputPassword").keyup(checkPasswort);
$("#inputPassword").keyup(checkPasswort);

function checkPasswort() {
    p = scorePassword($("#inputPassword").val());
    setPasswordStrengthIndicator(p);

    if (($("#inputPassword").val().length > 0 && $("#inputPassword").val().length < 6) ||
        $("#inputPassword").val() != $("#inputPassword2").val()) {
        $("#submitSettings").prop("disabled", true);
    } else {
        $("#submitSettings").prop("disabled", false);
    }

    if ($("#inputPassword").val() == $("#inputPassword2").val()) {
        $("#inputPassword2").css("background-color", "rgba(154, 205, 50, 0.2)");
    }
    else {
        $("#inputPassword2").css("background-color", "rgba(205, 50, 50, 0.2)");
    }
}


function setPasswordStrengthIndicator(percentage) {
    $("#passwordStrengthIndicator").css("width", percentage + "%");

    if (-1 < percentage && percentage < 20) {
        $("#passwordStrengthIndicator").css("background-color", "red");
    }
    if (19 < percentage && percentage < 40) {
        $("#passwordStrengthIndicator").css("background-color", "orange");
    }
    if (39 < percentage && percentage < 70) {
        $("#passwordStrengthIndicator").css("background-color", "rgb(255, 203, 0);");
    }
    if (69 < percentage && percentage < 101) {
        $("#passwordStrengthIndicator").css("background-color", "green");
    }
}

function scorePassword(pass) {
    var score = 0;
    if (!pass)
        return score;

    // award every unique letter until 5 repetitions
    var letters = {};
    for (var i = 0; i < pass.length; i++) {
        letters[pass[i]] = (letters[pass[i]] || 0) + 1;
        score += 5.0 / letters[pass[i]];
    }

    // bonus points for mixing it up
    var variations = {
        digits: /\d/.test(pass),
        lower: /[a-z]/.test(pass),
        upper: /[A-Z]/.test(pass),
        nonWords: /\W/.test(pass)
    };

    variationCount = 0;
    for (var check in variations) {
        variationCount += (variations[check] == true) ? 1 : 0;
    }
    score += (variationCount - 1) * 10;

    percentage = parseInt(score);

    percentage *= 0.6;
    if (percentage > 100) {
        percentage = 100;
    }
    return parseInt(percentage);
}
