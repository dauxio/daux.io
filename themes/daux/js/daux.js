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

(function() {
    function debounce(func, wait) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
            };

            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
       };
    };

    var navItems = document.querySelectorAll('.Nav__item.has-children i.Nav__arrow');

    function _toggleSubMenu(ev) {
        if (ev.preventDefault !== undefined) {
            ev.preventDefault();
        }

        var parent = ev.target.parentNode.parentNode;
        var subNav = parent.querySelector('ul.Nav');

        if (ev.preventDefault !== undefined && parent.classList.contains('Nav__item--open')) {
            // Temporarily set the height so the transition can work.
            subNav.style.height = subNav.scrollHeight + 'px';
            subNav.style.transitionDuration = Math.max(subNav.scrollHeight, 150) + 'ms';
            subNav.style.height = '0px';
            parent.classList.remove('Nav__item--open');
        } else {
            if (ev.preventDefault !== undefined) {
                subNav.style.transitionDuration = Math.max(subNav.scrollHeight, 150) + 'ms';
                // After the transition finishes set the height to auto so child
                // menus can expand properly.
                subNav.addEventListener('transitionend', _setHeightToAuto);
                subNav.style.height = subNav.scrollHeight + 'px';
                parent.classList.add('Nav__item--open');
            } else {
                // When running at page load the transitions don't need to fire and
                // the classList doesn't need to be altered.
                subNav.style.height = 'auto';
            }
        }
    }

    function _setHeightToAuto(ev) {
        if (ev.target.style.height !== '0px') {
            ev.target.style.height = 'auto';
        }

        ev.target.removeEventListener('transitionend', _setHeightToAuto);
    }

    // Go in reverse here because on page load the child nav items need to be
    // opened first before their parents so the height on the parents can be
    // calculated properly.
    for (var i = navItems.length - 1, cur; i >= 0; i--) {
        cur = navItems[i];
        cur.addEventListener('click', _toggleSubMenu);

        if (cur.parentNode.parentNode.classList.contains('Nav__item--open')) {
            _toggleSubMenu({ target: cur });
        }
    }
})();

(function() {
    var trigger = document.querySelector('.Collapsible__trigger');

    if (!trigger) {
        return;
    }

    content = document.querySelector('.Collapsible__content');

    trigger.addEventListener('click', function(ev) {
        if (content.classList.contains('Collapsible__content--open')) {
            content.style.height = 0;
            content.classList.remove('Collapsible__content--open');
        } else {
            content.style.transitionDuration = Math.max(content.scrollHeight, 150) + 'ms';
            content.style.height = content.scrollHeight + 'px';
            content.classList.add('Collapsible__content--open');
        }
    });
})();
