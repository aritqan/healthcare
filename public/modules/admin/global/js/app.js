const elements          = document.getElementsByClassName('style-files');
const bodyDarkClass     = 'dark-mode';
const _DARK_MODE_       = 'dark';
const _LIGHT_MODE_      = 'light';

// Toggle Dark And Light Mode
toggleMode = () => {
    $('body').toggleClass(bodyDarkClass);

    let mode = $('body').hasClass(bodyDarkClass) ? _DARK_MODE_ : _LIGHT_MODE_;

    changeThemeMode(mode, elements);
}

// Change Them Mode And Save Preferred Mode In Local Storage
changeThemeMode = (mode = _LIGHT_MODE_, elements) => {

    if(isEmpty(elements)) {
        return false;
    }

    let elementsArray = elements;

    // Convert HTMLCollection to array
    if(! Array.isArray(elementsArray)) {
        elementsArray = Array.from(elements);
    }

    elementsArray.forEach(element => {
        let fileName        = element.getAttribute('href');
        let newFileName     = toggleDarkModeExtension(fileName, mode);
        element.setAttribute('href', newFileName);
    });

    localStorage.setItem('mode', mode);

}

// Update style File Name By Mode (Dark or Light)
toggleDarkModeExtension = (fileName, mode) => {

    if(mode == _DARK_MODE_) {
        return removeOrChangeCharacterFromString(fileName, 'bundle', 'dark.bundle')

    }else {
        return removeOrChangeCharacterFromString(fileName, '.dark')
    }

}

removeOrChangeCharacterFromString = (text, wordToRemoveOrChange, valueWillBeChanged = '') => {

    if(isEmpty(text) || isEmpty(wordToRemoveOrChange)) {
        return text;
    }

    // Create a regular expression with the specified word to match
    let regex = new RegExp("\\b" + wordToRemoveOrChange + "\\b", "g");

    // Use replace() to remove the word
    let modifiedString = text.replace(regex, valueWillBeChanged);

    return modifiedString;
}

// Check Value Is Empty
isEmpty = (value) => {
    if (value === null || value === undefined || (typeof value === 'string' && value.trim() === '') || (Array.isArray(value) && value.length === 0) || (typeof value === 'object' && Object.keys(value).length === 0)) {
        return true;
    }

    return false;
}

isNotEmpty = (value) => {
    return !isEmpty(value);
}

// Check Value Is Truth
isTruth = (value) => {
    if (value === true || value === 'true' || value === 1 || value === "1") {
        return true;
    }

    return false;
}

// Check Value Is False
isFalse = (value) => {
    return !isTruth(value);
}

isJSON = (value) => {
    return typeof value === 'object' && value !== null && !Array.isArray(value) && JSON.stringify(value).charAt(0) === '{' ? true : false;
}

isNumberKey = (event) => {
    var charCode = (event.which) ? event.which : event.keyCode;
    if (withPlus && charCode == 43) {
        return true;
    }
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

isPhoneKey = (event) => { // Accept only +, -, numbers
    var charCode = (event.which) ? event.which : event.keyCode;

    if (charCode > 31 && (charCode < 43 || charCode > 57))
        return false;
    return true;
}

// Check localStorage for the user's preferred mode
document.addEventListener('DOMContentLoaded', () => {
    const preferredMode = localStorage.getItem('mode');

    if (preferredMode === _DARK_MODE_) {
        $('body').addClass(bodyDarkClass);
    } else {
        $('body').removeClass(bodyDarkClass);
    }

    changeThemeMode(preferredMode, elements);
});

// When user type on input-upper class name, it will be converted to uppercase
$('.to-upper').on('input', function () {
    // keep the cursor position
    var start = this.selectionStart;
    var end = this.selectionEnd;

    $(this).val($(this).val().toUpperCase());
    this.setSelectionRange(start, end);
});

// When user type on input-upper class name, it will be converted to lowercase
$('.to-lower').on('input', function () {
    // keep the cursor position
    var start = this.selectionStart;
    var end = this.selectionEnd;

    $(this).val($(this).val().toLowerCase());
    this.setSelectionRange(start, end);
});

// convert space and dash to underscore
$('.space-to-underscore').on('input', function () {
    // keep the cursor position
    var start = this.selectionStart;
    var end = this.selectionEnd;

    $(this).val($(this).val().replace(/ /g, '_'));
    $(this).val($(this).val().replace(/-/g, '_'));
    this.setSelectionRange(start, end);
});

// convert space and underscore to dash
$('.space-underscore-to-dash').on('input', function () {
    // keep the cursor position
    var start = this.selectionStart;
    var end = this.selectionEnd;

    $(this).val($(this).val().replace(/ /g, '-'));
    $(this).val($(this).val().replace(/_/g, '-'));
    this.setSelectionRange(start, end);
});

// convert space to , (comma)
$('.space-to-comma').on('input', function () {
    // keep the cursor position
    var start = this.selectionStart;
    var end = this.selectionEnd;

    $(this).val($(this).val().replace(/ /g, ','));
    this.setSelectionRange(start, end);
});

// Accept only english letters and underscore
$('.only-english-letters').on('input', function () {
    // keep the cursor position
    var start = this.selectionStart;
    var end = this.selectionEnd;

    $(this).val($(this).val().replace(/[^A-Za-z_-]/g, ''));
    this.setSelectionRange(start, end);
});

// Accept only english letters and comma and underscore
$('.only-english-letters-comma-underscore').on('input', function () {
    // keep the cursor position
    var start = this.selectionStart;
    var end = this.selectionEnd;

    $(this).val($(this).val().replace(/[^A-Za-z,_-]/g, ''));
    this.setSelectionRange(start, end);
});

// Accept only english letters and underscore and numbers
$('.only-english-letters-and-numbers').on('input', function () {
    // keep the cursor position
    var start = this.selectionStart;
    var end = this.selectionEnd;

    $(this).val($(this).val().replace(/[^A-Za-z0-9_-]/g, ''));
    this.setSelectionRange(start, end);
});

