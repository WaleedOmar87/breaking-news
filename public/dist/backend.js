/**
 * Settings Page Scripts
 **/
class BreakingNews {
	/**
	 * Assign jquery locally
	 **/
	constructor() {
		this.initColorPicker();
	}

	/**
	 * Initialize wordpress color picker
	 **/
	initColorPicker() {
		jQuery(".color-picker").wpColorPicker();
	}
}

document.addEventListener("DOMContentLoaded", () => {
	let init = new BreakingNews();
});
