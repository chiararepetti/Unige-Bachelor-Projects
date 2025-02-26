const allStar = document.querySelectorAll('.rating .star')
const ratingValue = document.querySelector('.rating input')

allStar.forEach((star, idx)=> {
	star.addEventListener('click', function () {
		let click = 0
		ratingValue.value = idx + 1

		allStar.forEach(star=> {
			star.classList.remove('checked')
			star.classList.remove('active')
		})
		for(let i=0; i<allStar.length; i++) {
			if(i <= idx) {
				allStar[i].classList.add('checked')
				allStar[i].classList.add('active')
			} else {
				allStar[i].style.setProperty('--i', click)
				click++
			}
		}
	})
})