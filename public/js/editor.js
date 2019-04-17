let socket;
let canvas;
let width;
let height;
let n;
let wunit;
let hunit;

function hexToRgb(hex) {
	var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
	return [
		parseInt(result[1], 16),
		parseInt(result[2], 16), 
		parseInt(result[3], 16)
	];
}

function saveImage() {
	ssend({
		type: 'save'
	});
}

let handler = {
	mod: (msg) => {
		let ctx = canvas.getContext("2d");
		let x = parseInt(msg.x * wunit);
		let y = parseInt(msg.y * hunit);
		let w = parseInt((msg.x+1) * wunit) - x;
		let h = parseInt((msg.y+1) * hunit) - y;
		let color = 'rgb(';
		for (let k = 0; k < 3; k++) {
			color += msg.color[k].toString();
			if (k < 2)
				color += ',';
		}
		color += ')';
		ctx.fillStyle = color;
		ctx.beginPath();
		ctx.rect(x, y, w, h);
		ctx.fill();
	},
	users: (msg) => {
		let list = msg.users;
		let ele = $('#collaborators li');
		ele.each(function () {
			$(this).children('.badge').remove();
			let id = $(this).attr('data-id');
			for (let i in list) {
				if (list[i].id == id) {
					$(this).append($('<span class="new badge" data-badge-caption="Active"></span>'));
					break;
				}
			}
		});
	},
	save: (msg) => {
		$('#save').tooltip();
		$('#save').tooltip('open');
		$('#save').unbind('mouseleave');
		$('#save').mouseleave(() => {
			$('#save').unbind('mouseleave');
			setTimeout(() => {
				$('#save').tooltip('destroy');
			}, 500);
		});
	},
	error: (msg) => {
		console.log(msg);
	}
};

function register(type, func) {
	handler[type] = func;
}

function ssend(msg) {
	console.log('Send', msg);
	if (socket.readyState == socket.OPEN) {
		socket.send(JSON.stringify(msg));
	}
}

async function slogin() {
	let success = false;
	register('success', (msg) => {
		success = true;
		width = canvas.width;
		height = canvas.height;
		n = msg.size;
		wunit = width / n;
		hunit = height / n;
		renderToScreen(canvas, msg.data, msg.size);
		register('success', null);
	});
	ssend({
		type: 'login',
		username: username,
		token: token,
		imageid: imageid
	});
	await new Promise((resolve, rejcet) => {
		let id = setInterval(() => {
			if (success) {
				clearInterval(id);
				resolve();
			}
		}, 10);
	});
}

let down = false;
let px = -1, py = -1;
$(document).mousedown(function() {
	down = true;
}).mouseup(function() {
	down = false;
	px = -1;
	py = -1;
});

$(document).ready(() => {
	canvas = document.getElementById('canvas');
	socket = new WebSocket('wss://ourpixels-node.herokuapp.com/');
	socket.onopen = async () => {
		socket.onmessage = (msg) => {
			msg = JSON.parse(msg.data);
			if (handler[msg.type]) {
				handler[msg.type](msg);
			}
		};
		await slogin();
		canvas.style.cursor = 'crosshair';
		let penhandler = (event) => {
			let x = event.pageX - canvas.offsetLeft;
			let y = event.pageY - canvas.offsetTop;
			let xc = Math.floor(x / wunit);
			let yc = Math.floor(y / hunit);
			if (px == xc && py == yc)
				return;
			px = xc;
			py = yc;
			let color = $('#color').val();
			let msg = {
				type: 'mod',
				x: xc,
				y: yc,
				color: hexToRgb(color)
			};
			ssend(msg);
		};
		canvas.addEventListener('mousedown', penhandler)
		canvas.addEventListener('mousemove', (event) => {
			if (!down)
				return;
			penhandler(event);
		});
	};
});