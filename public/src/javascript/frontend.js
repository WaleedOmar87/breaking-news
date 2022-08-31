/**
 * Front-end Javascript
 **/
class breakingNewsFrontend {
	constructor() {
		this.closeSection();
	}
	// Close breaking news section
	closeSection() {
		let sectionContainer = document.querySelector("#bn_frontend");
		let closeButton = sectionContainer.querySelector(".close");
		if (closeButton != null) {
			closeButton.addEventListener("click", (e) => {
				sectionContainer.classList.add("hide");
			});
		}
	}
}

/**
 * Init
 **/
document.addEventListener("DOMContentLoaded", () => {
	let toggleCloseSection = new breakingNewsFrontend();
});
