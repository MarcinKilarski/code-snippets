/**
 * Output information about people on the results page into a CSV format.
 */

let people = document.querySelectorAll('.org-people-profiles-module__profile-list .org-people-profiles-module__profile-item')
let csv = ''

// use foreach
for (let i = 0; i < 900; i++) {
	let person = people[i]

	if (person) {
		let button = person.querySelector('.artdeco-button')

		if (button) {
			let name = person.querySelector('.org-people-profile-card__profile-title').innerText
			let role = person.querySelector('.artdeco-entity-lockup__subtitle').innerText
			let img = person.querySelector('.artdeco-entity-lockup__image img').src
			let link = person.querySelector('a').href

			//     console.log(role)
			csv += `${name};${role};${img};${link};\n`
		}
	}
}

console.log(csv)
