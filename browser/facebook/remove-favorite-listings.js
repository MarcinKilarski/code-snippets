/*
 * Remove saved and yours ads from the current view
 *
 * TODO: also remove ads with he edit button
 */
const timer = (ms) => new Promise((res) => setTimeout(res, ms)),
    getAllAds = document.querySelectorAll('.bq4bzpyk.j83agx80.btwxx1t3.lhclo0ds.jifvfom9.muag1w35.dlv3wnog.enqfppq2.rl04r1d5 a')

console.log('Number of found ads:', getAllAds.length)

const handleClick = (ad) => {
    let getSaveButton = document.querySelector("div[aria-label='Save']"),
        getCloseButton = document.querySelector("div[aria-label='Close']"),
        isSavedButtonSelected = getSaveButton.getAttribute('aria-pressed')

    getCloseButton.click()
    return isSavedButtonSelected == 'true'
}

async function handleSave() {
    await timer(50)
    let getSaveButton = document.querySelector("div[aria-label='Save']")
    getSaveButton || (await handleSave())
}

async function deleteSavedAds() {
    for (ad of getAllAds) {
        await timer(300)
        ad.click()
        await handleSave()
        let isSavedButtonSelected = handleClick()
        isSavedButtonSelected && ad.remove()
    }

    const getNewAds = document.querySelectorAll('.bq4bzpyk.j83agx80.btwxx1t3.lhclo0ds.jifvfom9.muag1w35.dlv3wnog.enqfppq2.rl04r1d5 a')
    console.log('Number of found new ads:', getNewAds.length)
}
deleteSavedAds()
