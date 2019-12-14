module.exports = function() {
	this.times = {};

	var begins = {};

	this.start = function(key) {
		begins[key] = process.hrtime();
	};

	this.stop = function(key) {
		if (key in begins) {
			var t = process.hrtime(begins[key]);
			t = t[0] + t[1] * 1e-9;
			if (key in this.times) this.times[key] += t;
			else this.times[key] = t;
		}
	}
};
