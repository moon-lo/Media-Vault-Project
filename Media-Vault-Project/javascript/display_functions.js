/**
 * Disable file management buttons if no file has been selected.
 *
 * @param setDisabled - boolean - true if no file has been selected, therefore disable.
 *
 * @author James Galloway
 */
function setFileManButtons(setDisabled) {
    var inputs = document.getElementById("fileManForm").elements;

    for (var i = 0, element; element = inputs[i++];) {
        if (element !== document.getElementById("newFolderButton")) {
            element.disabled = setDisabled;

            if (setDisabled) {
                element.className += "inactive";
            } else {
                element.className += "active";
            }
        }
    }
} // end setFileManButtons