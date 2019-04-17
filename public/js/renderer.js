const radix = 16;

const imageConverter = {
	decode: (data, n) => {
		let ret = [];
		let ptr = 0;
		for (let i=0; i<n; i++) {
			let arr = [];
			for (let j=0; j<n; j++) {
				let cur = [];
				for (let k=0; k<3; k++) {
					let tmp = data.substr(ptr, 2) || 'FF';
					cur.push(parseInt(tmp, radix));
					ptr += 2;
				}
				arr.push(cur);
			}
			ret.push(arr);
		}
		return ret;
	},
	encode: (object) => {
		let n = object.length;
		let ret = '';
		for (let i=0; i<n; i++) {
			let curi = object[i] || [];
			for (let j=0; j<n; j++) {
				let curj = curi[j] || [];
				for (let k=0; k<3; k++) {
					let cur = curj[k];
					if (cur == undefined)
						cur = 255;
					let tmp = cur.toString(radix);
					while (tmp.length < 2) {
						tmp = '0' + tmp;
					}
					ret += tmp;
				}
			}
		}
		return ret;
	}
};

function renderToScreen(canvas, data, n) {
	let width = canvas.width;
	let height = canvas.height;
	if (typeof(data) == 'string')
	 data = imageConverter.decode(data, n);
	let wunit = width / n;
	let hunit = height / n;
	let ctx = canvas.getContext("2d");
	for (let i = 0; i < n; i++) {
		let x = parseInt(wunit * i);
		let w = parseInt(wunit * (i+1)) - x;
		for (let j = 0; j < n; j++) {
			let y = parseInt(hunit * j);
			let h = parseInt(hunit * (j+1)) - y;
			let color = 'rgb(';
			for (let k = 0; k < 3; k++) {
				color += data[i][j][k].toString();
				if (k < 2)
					color += ',';
			}
			color += ')';
			ctx.fillStyle = color;
			ctx.beginPath();
			ctx.rect(x, y, w, h);
			ctx.fill();
		}
	}
}