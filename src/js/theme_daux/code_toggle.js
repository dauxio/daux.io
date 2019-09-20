const codeBlocks = document.querySelectorAll(".s-content pre");
const toggleCodeSection = document.querySelector(".CodeToggler");

function setCodeBlockStyle(
    codeBlockState,
    toggleCodeBlockBtnList,
    toggleCodeBlockBtnFloat,
    toggleCodeBlockBtnBelow,
    toggleCodeBlockBtnHide,
    codeBlockView
) {
    for (let a = 0; a < toggleCodeBlockBtnList.length; a++) {
        toggleCodeBlockBtnList[a].classList.remove("Button--active");
    }
    let hidden;
    switch (codeBlockState) {
        case true: // Show code blocks below (flowed); checkbox
            hidden = false;
            break;
        case false: // Hidden code blocks; checkbox
            hidden = true;
            break;
        case 2: // Show code blocks inline (floated)
            toggleCodeBlockBtnFloat.classList.add("Button--active");
            codeBlockView.classList.add("Columns__right--float");
            codeBlockView.classList.remove("Columns__right--full");
            hidden = false;
            break;
        case 1: // Show code blocks below (flowed)
        case "checked":
            toggleCodeBlockBtnBelow.classList.add("Button--active");
            codeBlockView.classList.remove("Columns__right--float");
            codeBlockView.classList.add("Columns__right--full");
            hidden = false;
            break;
        case 0: // Hidden code blocks
        default:
            toggleCodeBlockBtnHide.classList.add("Button--active");
            codeBlockView.classList.remove("Columns__right--float");
            codeBlockView.classList.add("Columns__right--full");
            hidden = true;
            break;
    }
    for (let a = 0; a < codeBlocks.length; a++) {
        if (hidden) {
            codeBlocks[a].classList.add("Hidden");
        } else {
            codeBlocks[a].classList.remove("Hidden");
        }
    }
    try {
        localStorage.setItem("codeBlockState", +codeBlockState);
    } catch (e) {
        // local storage operations can fail with the file:// protocol
    }
}

function enableToggler() {
    const toggleCodeBlockBtnList = toggleCodeSection.querySelectorAll(
        ".CodeToggler__button"
    );
    const toggleCodeBlockBtnSet = toggleCodeSection.querySelector(
        ".CodeToggler__button--main"
    ); // available when floating is disabled
    const toggleCodeBlockBtnHide = toggleCodeSection.querySelector(
        ".CodeToggler__button--hide"
    );
    const toggleCodeBlockBtnBelow = toggleCodeSection.querySelector(
        ".CodeToggler__button--below"
    );
    const toggleCodeBlockBtnFloat = toggleCodeSection.querySelector(
        ".CodeToggler__button--float"
    );
    const codeBlockView = document.querySelector(".Columns__right");
    const floating = document.body.classList.contains("with-float");

    const setStyle = style => {
        setCodeBlockStyle(
            style,
            toggleCodeBlockBtnList,
            toggleCodeBlockBtnFloat,
            toggleCodeBlockBtnBelow,
            toggleCodeBlockBtnHide,
            codeBlockView
        );
    };

    if (floating) {
        toggleCodeBlockBtnHide.addEventListener(
            "click",
            () => {
                setStyle(0);
            },
            false
        );
        toggleCodeBlockBtnBelow.addEventListener(
            "click",
            () => {
                setStyle(1);
            },
            false
        );
        toggleCodeBlockBtnFloat.addEventListener(
            "click",
            () => {
                setStyle(2);
            },
            false
        );
    } else {
        toggleCodeBlockBtnSet.addEventListener(
            "change",
            ev => {
                setStyle(ev.target.checked);
            },
            false
        );
    }

    let codeBlockState = null;
    try {
        codeBlockState = localStorage.getItem("codeBlockState");
    } catch (e) {
        // local storage operations can fail with the file:// protocol
    }

    if (codeBlockState) {
        codeBlockState = parseInt(codeBlockState, 10);
    } else {
        codeBlockState = floating ? 2 : 1;
    }

    if (!floating) {
        codeBlockState = !!codeBlockState;
    }

    setCodeBlockStyle(
        codeBlockState,
        toggleCodeBlockBtnList,
        toggleCodeBlockBtnFloat,
        toggleCodeBlockBtnBelow,
        toggleCodeBlockBtnHide,
        codeBlockView
    );
}

if (toggleCodeSection) {
    if (codeBlocks.length) {
        enableToggler();
    } else {
        toggleCodeSection.classList.add("Hidden");
    }
}
