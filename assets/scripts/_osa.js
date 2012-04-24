// _osa.js
// Consider this the "global javascript functions" file
var osa = {
	// Turn "A Text String - Like This" into "a-text-string-like-this"
	slug:function(text) {
		return text
			.toLowerCase()
			.replace(/[^\w ]+/g, '')
			.replace(/ +/g, '-');
	}
}