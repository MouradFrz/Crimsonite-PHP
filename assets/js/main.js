const button = document.querySelector('#toggle')
const dropdown = document.querySelector('.dropdown')

button.addEventListener('click',()=>{
    dropdown.classList.toggle('show')
})


const simpleAnimate = document.querySelectorAll('.js-fade-in')
simpleAnimate.forEach((e)=>{
    gsap.from(e,{duration:0.6,opacity:0,y:50,scrollTrigger:{
        trigger:e,
        start:'top 80%'
    }})
})
gsap.from('nav',{duration:1,opacity:0})
gsap.from('.js-animate',{duration:0.6,opacity:0,y:50,stagger:0.1,scrollTrigger:{trigger:'.js-animate'}})

// gsap.from('.js-stag',{duration:0.6,opacity:0,y:50,stagger:0.2})


