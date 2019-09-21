const codeBlocks = document.querySelectorAll(".s-content pre");
const toggleCodeSection = document.querySelector(".CodeToggler");

const LOCAL_STORAGE_KEY = "daux_code_blocks_hidden";

function setCodeBlockStyle(hidden) {
    for (let a = 0; a < codeBlocks.length; a++) {
        codeBlocks[a].classList.toggle("Hidden", hidden);
    }
    try {
        localStorage.setItem(LOCAL_STORAGE_KEY, hidden);
    } catch (e) {
        // local storage operations can fail with the file:// protocol
    }
}

function enableToggler() {
    const toggleCodeBlockBtnSet = toggleCodeSection.querySelector(
        ".CodeToggler__button--main"
    ); // available when floating is disabled

    toggleCodeBlockBtnSet.addEventListener(
        "change",
        ev => {
            setCodeBlockStyle(!ev.target.checked);
        },
        false
    );

    let hidden = false;
    try {
        hidden = localStorage.getItem(LOCAL_STORAGE_KEY);

        if (hidden === "false") {
            hidden = false;
        } else if (hidden === "true") {
            hidden = true;
        }

        if (hidden) {
            setCodeBlockStyle(!!hidden);
            toggleCodeBlockBtnSet.checked = !hidden;
        }
    } catch (e) {
        // local storage operations can fail with the file:// protocol
    }
}

if (toggleCodeSection) {
    if (codeBlocks.length) {
        enableToggler();
    } else {
        toggleCodeSection.classList.add("Hidden");
    }
}
