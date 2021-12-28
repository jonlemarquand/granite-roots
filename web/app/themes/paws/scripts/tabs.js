//For Tabs Gutenberg block
docReady(function () {//Above IE8

	var navigationTabs = document.getElementsByClassName("block-tabs__nav-item");
	Array.from(navigationTabs).forEach(function (navigationTab) {
		navigationTab.addEventListener('click', function (navigationTab){
			changeTab(navigationTab);
		});
	});

	const changeTab = (e) => {
		if (e) {
			let target = e.target;
			target = target.getAttribute('data-tab-target');
			console.log(['Clicked target : '], target);

			//TODO: parent level awareness in case there are other Block tabs in the Editor
			let selectedTabNavItem = document.querySelector(".block-tabs__nav-item[data-tab-target='" + target + "']");
			let selectedTab = document.querySelector(".block-tab[data-tab-index='" + target + "']");

			//1. Hide active tab
			[...document.getElementsByClassName('block-tab')].map(x => x.setAttribute("data-tab-visible", false));
			[...document.getElementsByClassName('block-tabs__nav-item')].map(x => x.classList.remove('active'));

			//2. Show tab
			selectedTab.setAttribute("data-tab-visible", true);
			selectedTabNavItem.classList.add("active");
		}
	}

});