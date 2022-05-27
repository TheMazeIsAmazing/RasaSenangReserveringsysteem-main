window.addEventListener('load', init)

function init() {
    checkPassword()
}

function checkPasswordSecurity(passwordText){
    let passwordPass = true;
    // Min length
    if(passwordText.length > 8 ){
        document.getElementById("passwordRequirementMinLength").style.display = "none";

        // No Common passwords:
        let listOfCommonPasswords = ["123456", "123456789", "password", "password123", "passw0rd", "12345678", "111111", "123123", "12345", "1234567890", "1234567", "qwerty", "abc123", "000000", "1234", "iloveyou", "password1", "123", "123321", "654321", "qwertyuiop", "123456a", "a123456", "666666", "asdfghjkl", "987654321", "zxcvbnm", "112233", "20100728", "123123123", "princess", "123abc", "123qwe", "sunshine", "121212", "dragon", "1q2w3e4r", "159753", "0123456789", "pokemon", "qwerty123", "monkey", "1qaz2wsx", "abcd1234", "aaaaaa", "soccer", "123654", "12345678910", "shadow", "102030", "11111111", "asdfgh", "147258369", "qazwsx", "qwe123", "football", "baseball", "person", "government", "company", "number", "problem", "0123456"];

        if(listOfCommonPasswords.indexOf(passwordText.toLowerCase()) === -1){
                document.getElementById("passwordRequirementCommonPasswords").style.display = "none";
            } else{
                passwordPass = false;
                document.getElementById("passwordRequirementCommonPasswords").style.display = "list-item";
            }
    } else{
        document.getElementById("passwordRequirementMinLength").style.display = "list-item";
        document.getElementById("passwordRequirementCommonPasswords").style.display = "list-item";
        passwordPass = false;
    }

    // Must contain one number and one letter
    if(/\w+/.test(passwordText) && /\d+/.test(passwordText)){
        document.getElementById("passwordRequirementChars").style.display = "none";
    }else{
        passwordPass = false;
        document.getElementById("passwordRequirementChars").style.display = "list-item";
    }

    document.getElementById("passwordInfoPanel").style.display = passwordPass ? "none": "block";
    return passwordPass;
}


function setPasswordStrengthBar(password, score){
    let barMaxLength = 210;
    let barElement = document.getElementById("passwordStrengthBar");
    let textElement = document.getElementById("passwordStrengthText");

    if(password.length === 0){
        barElement.style.width = 0;
        textElement.innerText = "";
        return;
    }

    barElement.style.width = barMaxLength * ((score + 1) / 5.0) + "px";

    if(score <= 0){
        barElement.style.backgroundColor = "#a62b16";
        textElement.innerText = "Zwak";
    }else if (score <= 1){
        barElement.style.backgroundColor = "#d68b13";
        textElement.innerText = "Matig";
    }else if (score <= 2){
        barElement.style.backgroundColor = "#dbce16";
        textElement.innerText = "Gemiddeld";
    }else if (score <= 3){
        barElement.style.backgroundColor = "#a1d13b";
        textElement.innerText = "Goed";
    }else{
        barElement.style.backgroundColor = "#33a100";
        textElement.innerText = "Geweldig";
    }
}


function checkPassword(){
    let passwordText = document.getElementById("passwordField").value;
    let siteSpecificWords = [''];
    let passwordDifficulty = zxcvbn(passwordText, siteSpecificWords);
    setPasswordStrengthBar(passwordText, passwordDifficulty.score);
    checkPasswordSecurity(passwordText);
}