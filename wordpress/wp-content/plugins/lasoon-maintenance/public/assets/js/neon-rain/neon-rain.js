// Utilities
const randomNum = (min, max) => Math.floor(Math.random() * (max - min + 1) + min)
const randomColor = colors => colors[Math.floor(Math.random() * colors.length)];

const colors = [
    '#9C4AFF',
    '#8C43E6',
    '#7638C2',
    '#5E2C99',
    '#492378'
]

//Setup
const canvas = document.querySelector('canvas')
const c = canvas.getContext('2d')

canvas.width = innerWidth
canvas.height = innerHeight

addEventListener('resize', () => {
    canvas.width = innerWidth
    canvas.height = innerHeight

})

//Controls 

const controls = {
    count: -10,
    velocity: 5
}


//Drops
class Drop {
    constructor(x, y, dy, thickness, length, color) {
        this.x = x
        this.y = y
        this.dy = dy
        this.thickness = thickness
        this.color = color
        this.length = length
        this.gravity = .4
    }
}

Drop.prototype.draw = function () {
    c.beginPath()
    c.strokeStyle = this.color
    c.lineWidth = this.thickness
    c.moveTo(this.x, this.y)
    c.lineTo(this.x, this.y - this.length)
    c.stroke()
    c.closePath()
}

Drop.prototype.update = function () {
    if (this.dy > 0) this.dy += this.gravity
    this.y += this.dy

    if (this.y > canvas.height - 100) this.splash(this.x, this.y + (this.length * 2))

    this.draw()
}

Drop.prototype.splash = function (x, y) {
    for (let i = 0; i < 5; i++) {
        const dx = randomNum(-3, 3)
        const dy = randomNum(-1, -5)
        const radius = randomNum(1, 3)

        droplets.push(new Droplet(x, y, dx, dy, radius, randomColor(colors)))
    }
}

// Droplets
class Droplet {
    constructor(x, y, dx, dy, radius, color) {
        this.x = x
        this.y = y
        this.dx = dx
        this.dy = dy
        this.radius = radius
        this.color = color
        this.gravity = .1
    }
}

Droplet.prototype.draw = function () {
    c.beginPath()
    c.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false)
    c.fillStyle = this.color
    c.fill()
    c.closePath()
}

Droplet.prototype.update = function () {
    this.dy += this.gravity
    this.y += this.dy * this.friction
    this.x += this.dx

    this.draw()
}

let droplets = []
let drops = []
let ticker = 0
const init = () => {
    drops = []
    droplets = []
}

const animate = () => {
    requestAnimationFrame(animate)
    c.fillStyle = 'rgba(33, 33, 33, .3)'; //Lower opacity creates a longer tail
    c.fillRect(0, 0, canvas.width, canvas.height);

    drops.forEach((drop, index) => {
        drop.update()
        if (drop.y >= canvas.height + drop.length) drops.splice(index, 1)
    })

    // Timing between drops
    ticker++
    let count = controls.count === 0 ? 0 : randomNum(controls.count + 5, controls.count)
    if (ticker % count == 0) {
        const x = randomNum(0, innerWidth)
        const y = 0
        const dy = controls.velocity === 0 ? 0 : randomNum(controls.velocity, controls.velocity + 10)
        const thickness = randomNum(3, 5)
        const length = randomNum(20, 50)

        drops.push(new Drop(x, y, dy, thickness, length, randomColor(colors)))
    }

    droplets.forEach((droplet, index) => {
        droplet.update()
        if (droplet.y >= canvas.height) droplets.splice(index, 1)
    })
}

animate()