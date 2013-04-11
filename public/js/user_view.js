var user_view = {
	init:function() {
		var ua = table_pagination.init({
			table: 'user_achievements', 
			url: '/user/achievements/' + user_id
		});
		var uc = table_pagination.init({
			table: 'user_comments', 
			url: '/user/comments/' + user_id
		});
		var uca = table_pagination.init({
			table: 'user_created_achievements', 
			url: '/user/created_achievements/' + user_id
		});
	}
}

// Psuedo Class
var table_pagination = {
	init:function(config) {
		var instance = {
			config: {
				table: null, // Table Element
				next: null, // Next Button Element
				previous: null, // Previous Button Element
				current: 0, // Where we currently are, increment by per_page
				per_page: 10, // Items Per page
				url: null, // Where to ajax for more information
				max_records: 0, // Total number of items
			},
			init:function(config)
			{
				// Merge the configs
				$.extend(this.config, config);

				// Can't do anything without the table
				if (this.config.table === null)
					return false;

				this.config.table = $('#' + this.config.table);
				this.config.max_records = this.config.table.data('total');

				// Assume next and previous
				if (this.config.next === null)
					this.config.next = this.config.table.find('tfoot .next');
				if (this.config.previous === null)
					this.config.previous = this.config.table.find('tfoot .previous');

				if (this.draw_on_load === true)
					this.draw();

				this.events();
			},
			events:function() {
				var that = this;

				this.config.next.click(function(event) {
					event.preventDefault();
					that.next($(this));
				});

				this.config.previous.click(function(event) {
					event.preventDefault();
					that.previous($(this));
				});
			},
			next:function(el) {
				if (el.hasClass('disabled')) 
					return;

				// Increment the current page
				this.config.current += this.config.per_page;

				// Unlock Previous link - If they've clicked "next", then by extension "prev" exists
				this.config.previous.removeClass('disabled');

				// Lock Next Link, only if there's nowhere left to go
				if (this.config.current + this.config.per_page > this.config.max_records)
					this.config.next.addClass('disabled');

				this.draw();
			},
			previous:function(el) {
				if (el.hasClass('disabled')) 
					return;

				// Decrement the current page
				this.config.current -= this.config.per_page;

				// Unlock Next link - If they've clicked "prev", then by extension "next" exists
				this.config.next.removeClass('disabled');

				// Lock Previous Link, only if there's nowhere left to go
				if (this.config.current - this.config.per_page < 0)
					this.config.previous.addClass('disabled');

				this.draw();
			},
			draw:function() {
				var that = this;

				// TODO loader sprite?

				$.ajax({
					url: this.config.url + '/' + this.config.per_page + '/' + this.config.current,
					dataType: 'json',
					success:function(json) {
						that.config.table.find('tbody').html(json.html);
					}
				});
			}
		};
		instance.init(config);
		return instance;
	}
}

$(user_view.init);