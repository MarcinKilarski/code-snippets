/*
 * Facebook remove listings that are older than one week
 */
let getListingsAge = document.querySelectorAll('._uc9._214v.fsm.fwn.fcg'),
    blacklistAge = 'over a week ago'

for (let i = 0, max = getListingsAge.length; i < max; i++) {
    let age = getListingsAge[i].innerText,
        card = getListingsAge[i].closest('._7yc._3ogd')

    if (false !== age.includes(blacklistAge)) {
        console.log(age)
        card.remove()
    }
}
