// banlist

module.exports = function() {
	var list = {};

	this.add = function(key, time) {
		if (key in list) clearTimeout(list[key]);

		list[key] = setTimeout(function() {
			delete list[key];
		}, time);
	};

	this.remove = function(key) {
		if (key in list) {
			clearTimeout(list[key])
			delete list[key];
		}
	};

	this.banned = function(key) {
		return key in list;
	}
};
