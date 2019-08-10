/** global localStorage, hljs */

if (hljs) {
    hljs.initHighlightingOnLoad();
}

(function() {
    var codeBlocks = document.querySelectorAll(".s-content pre");
    var toggleCodeSection = document.querySelector(".CodeToggler");
    if (!toggleCodeSection) {
        return;
    } else if (!codeBlocks.length) {
        toggleCodeSection.classList.add("Hidden");
        return;
    }

    var toggleCodeBlockBtnList = toggleCodeSection.querySelectorAll(".CodeToggler__button");
    var toggleCodeBlockBtnSet = toggleCodeSection.querySelector(".CodeToggler__button--main"); // available when floating is disabled
    var toggleCodeBlockBtnHide = toggleCodeSection.querySelector(".CodeToggler__button--hide");
    var toggleCodeBlockBtnBelow = toggleCodeSection.querySelector(".CodeToggler__button--below");
    var toggleCodeBlockBtnFloat = toggleCodeSection.querySelector(".CodeToggler__button--float");
    var codeBlockView = document.querySelector(".Columns__right");
    var floating = document.body.classList.contains("with-float");

    function setCodeBlockStyle(codeBlockState) {
        for (var a = 0; a < toggleCodeBlockBtnList.length; a++) {
            toggleCodeBlockBtnList[a].classList.remove("Button--active");
        }
        switch (codeBlockState) {
            case true: // Show code blocks below (flowed); checkbox
                var hidden = false;
                break;
            case false: // Hidden code blocks; checkbox
                var hidden = true;
                break;
            case 2: // Show code blocks inline (floated)
                toggleCodeBlockBtnFloat.classList.add("Button--active");
                codeBlockView.classList.add("Columns__right--float");
                codeBlockView.classList.remove("Columns__right--full");
                var hidden = false;
                break;
            case 1: // Show code blocks below (flowed)
            case "checked":
                toggleCodeBlockBtnBelow.classList.add("Button--active");
                codeBlockView.classList.remove("Columns__right--float");
                codeBlockView.classList.add("Columns__right--full");
                var hidden = false;
                break;
            case 0: // Hidden code blocks
            default:
                toggleCodeBlockBtnHide.classList.add("Button--active");
                codeBlockView.classList.remove("Columns__right--float");
                codeBlockView.classList.add("Columns__right--full");
                var hidden = true;
                break;
        }
        for (var a = 0; a < codeBlocks.length; a++) {
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
    if (!floating) {
        toggleCodeBlockBtnSet.addEventListener("change", function(ev) {setCodeBlockStyle(ev.target.checked);}, false);
    } else {
        toggleCodeBlockBtnHide.addEventListener("click", function() {setCodeBlockStyle(0);}, false);
        toggleCodeBlockBtnBelow.addEventListener("click", function() {setCodeBlockStyle(1);}, false);
        toggleCodeBlockBtnFloat.addEventListener("click", function() {setCodeBlockStyle(2);}, false);
    }

    try {
        var codeBlockState = localStorage.getItem("codeBlockState");
    } catch (e) {
        // local storage operations can fail with the file:// protocol
        var codeBlockState = null;
    }
    if (!codeBlockState) {
        codeBlockState = floating ? 2 : 1;
    } else {
        codeBlockState = parseInt(codeBlockState);
    }
    if (!floating) {
        codeBlockState = !!codeBlockState;
    }

    setCodeBlockStyle(codeBlockState);
})();

$(function () {
    // Tree navigation
    $('.aj-nav').click(function (e) {
        e.preventDefault();
        $(this).parent().siblings().find('ul').slideUp();
        $(this).next().slideToggle();
    });

    // New Tree navigation
    $('ul.Nav > li.has-children > a > .Nav__arrow').click(function() {
        $(this).parent().parent().toggleClass('Nav__item--open');
        return false;
    });

    // Responsive navigation
    $('.Collapsible__trigger').click(function () {
        $('.Collapsible__content').slideToggle();
    });
});
