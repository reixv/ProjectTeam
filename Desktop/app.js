const nib = document.querySelector('.header .nav-bar .nav-list .nib');
const menu = document.querySelector('.header .nav-bar .nav-list ul'); 
const menu_item = document.querySelectorAll('.header .nav-bar .nav-list ul li a');
const header = document.querySelector('.header.container');

nib.addEventListener('click', () => {
	nib.classList.toggle('active');
	menu.classList.toggle('active');
});

document.addEventListener('scroll', () => {
	var scroll_position = window.scrollY;
	if (scroll_position > 250) {
		header.style.backgroundColor = '#FFFFFF';
	} else {
		header.style.backgroundColor = 'transparent';
	}
});

menu_item.forEach((item) => {
	item.addEventListener('click', () => {
		nib.classList.toggle('active');
		menu.classList.toggle('active');
	});
});