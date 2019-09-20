const trigger = document.querySelector(".Collapsible__trigger");

if (trigger) {
    const content = document.querySelector(".Collapsible__content");

    trigger.addEventListener("click", ev => {
        if (content.classList.contains("Collapsible__content--open")) {
            content.style.height = 0;
            content.classList.remove("Collapsible__content--open");
        } else {
            content.style.transitionDuration = `${Math.max(
                content.scrollHeight,
                150
            )}ms`;
            content.style.height = `${content.scrollHeight}px`;
            content.classList.add("Collapsible__content--open");
        }
    });
}
