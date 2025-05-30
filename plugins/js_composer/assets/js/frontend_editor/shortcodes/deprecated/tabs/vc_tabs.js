( function ( $ ) {
	'use strict';

	window.vc.cloneMethod_vc_tab = function ( data, model ) {
		data.params = _.extend({}, data.params );
		data.params.tab_id = vc_guid() + '-cl';
		if ( !_.isUndefined( model.get( 'active_before_cloned' ) ) ) {
			data.active_before_cloned = model.get( 'active_before_cloned' );
		}

		return data;
	};
	window.InlineShortcodeView_vc_tabs = window.InlineShortcodeView_vc_row.extend({
		events: {
			'click > :first > .vc_empty-element': 'addElement',
			'click > :first > .wpb_wrapper > .ui-tabs-nav > li': 'setActiveTab'
		},
		already_build: false,
		active_model_id: false,
		$tabsNav: false,
		active: 0,
		render: function () {
			_.bindAll( this, 'stopSorting' );
			this.$tabs = this.$el.find( '> .wpb_tabs' );
			window.InlineShortcodeView_vc_tabs.__super__.render.call( this );
			this.buildNav();

			return this;
		},
		buildNav: function () {
			var $nav = this.tabsControls();
			this.$tabs.find( '> .wpb_wrapper > .vc_element[data-tag="vc_tab"]' ).each( function ( key ) {
				$( 'li:eq(' + key + ')', $nav ).attr( 'data-m-id', $( this ).data( 'model-id' ) );
			});
		},
		changed: function () {
			if ( this.allowAddControlOnEmpty() && 0 === this.$el.find( '.vc_element[data-tag]' ).length ) {
				this.$el.addClass( 'vc_empty' ).find( '> :first > div' ).addClass( 'vc_empty-element' );
			} else {
				this.$el.removeClass( 'vc_empty' ).find( '> :first > div' ).removeClass( 'vc_empty-element' );
			}
			this.setSorting();
		},
		setActiveTab: function ( e ) {
			var $tab = $( e.currentTarget );
			this.active_model_id = $tab.data( 'm-id' );
		},
		tabsControls: function () {
			return this.$tabsNav ? this.$tabsNav : this.$tabsNav = this.$el.find( '.wpb_tabs_nav' );
		},
		buildTabs: function ( active_model ) {
			if ( active_model ) {
				this.active_model_id = active_model.get( 'id' );
				this.active = this.tabsControls().find( '[data-m-id=' + this.active_model_id + ']' ).index();
			}
			if ( false === this.active_model_id ) {
				var active_el = this.tabsControls().find( 'li:first' );
				this.active = active_el.index();
				this.active_model_id = active_el.data( 'm-id' );
			}
			if ( !this.checkCount() ) {
				window.vc.frame_window.vc_iframe.buildTabs( this.$tabs, this.active );
			}
		},
		checkCount: function () {
			return this.$tabs.find( '> .wpb_wrapper > .vc_element[data-tag="vc_tab"]' ).length != this.$tabs.find( '> .wpb_wrapper > .vc_element.vc_vc_tab' ).length;
		},
		beforeUpdate: function () {
			this.$tabs.find( '.wpb_tabs_heading' ).remove();
			window.vc.frame_window.vc_iframe.destroyTabs( this.$tabs );
		},
		updated: function () {
			window.InlineShortcodeView_vc_tabs.__super__.updated.call( this );
			this.$tabs.find( '.wpb_tabs_nav:first' ).remove();
			this.buildNav();
			window.vc.frame_window.vc_iframe.buildTabs( this.$tabs );
			this.setSorting();
		},
		rowsColumnsConverted: function () {
			_.each( window.vc.shortcodes.where({ parent_id: this.model.get( 'id' ) }), function ( model ) {
				if ( model.view.rowsColumnsConverted ) {
					model.view.rowsColumnsConverted();
				}
			});
		},
		addTab: function ( model ) {
			if ( this.updateIfExistTab( model ) ) {
				return false;
			}
			var $control = this.buildControlHtml( model ),
				$cloned_tab;
			// TODO: check if $cloned_tab is used
			// eslint-disable-next-line no-unused-vars
			if ( model.get( 'cloned' ) && ( $cloned_tab = this.tabsControls().find( '[data-m-id=' + model.get( 'cloned_from' ).id + ']' ) ).length ) {
				if ( !model.get( 'cloned_appended' ) ) {
					$control.appendTo( this.tabsControls() );
					model.set( 'cloned_appended', true );
				}
			} else {
				$control.appendTo( this.tabsControls() );
			}
			this.changed();

			return true;
		},
		cloneTabAfter: function ( model ) {
			this.$tabs.find( '> .wpb_wrapper > .wpb_tabs_nav > div' ).remove();
			this.buildTabs( model );
		},
		updateIfExistTab: function ( model ) {
			var $tab = this.tabsControls().find( '[data-m-id=' + model.get( 'id' ) + ']' );
			if ( $tab.length ) {
				$tab.attr( 'aria-controls', 'tab-' + model.getParam( 'tab_id' ) )
					.find( 'a' )
					.attr( 'href', '#tab-' + model.getParam( 'tab_id' ) )
					.text( model.getParam( 'title' ) );
				return true;
			}
			return false;
		},
		buildControlHtml: function ( model ) {
			// TODO: check if params is used
			// eslint-disable-next-line no-unused-vars
			var params = model.get( 'params' ),
				$tab = $( '<li data-m-id="' + model.get( 'id' ) + '"><a href="#tab-' + model.getParam( 'tab_id' ) + '"></a></li>' );
			$tab.data( 'model', model );
			$tab.find( '> a' ).text( model.getParam( 'title' ) );
			return $tab;
		},
		addElement: function ( e ) {
			if ( e && e.preventDefault ) {
				e.preventDefault();
			}
			new window.vc.ShortcodesBuilder()
				.create({
					shortcode: 'vc_tab',
					params: {
						tab_id: vc_guid() + '-' + this.tabsControls().find( 'li' ).length,
						title: this.getDefaultTabTitle()
					},
					parent_id: this.model.get( 'id' )
				})
				.render();
		},
		getDefaultTabTitle: function () {
			return window.i18nLocale.tab;
		},
		setSorting: function () {
			if ( this.hasUserAccess() ) {
				window.vc.frame_window.vc_iframe.setTabsSorting( this );
			}
		},
		stopSorting: function () {
			this.tabsControls().find( '> li' ).each( function ( key ) {
				var model = $( this ).data( 'model' );
				model.save({ order: key }, { silent: true });
			});
		},
		placeElement: function ( $view ) {
			var model = window.vc.shortcodes.get( $view.data( 'modelId' ) );
			if ( model && model.get( 'place_after_id' ) ) {
				$view.insertAfter( window.vc.$page.find( '[data-model-id=' + model.get( 'place_after_id' ) + ']' ) );
				model.unset( 'place_after_id' );
			} else {
				$view.insertAfter( this.tabsControls() );
			}
			this.changed();
		},
		removeTab: function ( model ) {
			if ( 1 === window.vc.shortcodes.where({ parent_id: this.model.get( 'id' ) }).length ) {
				return this.model.destroy();
			}
			var $tab = this.tabsControls().find( '[data-m-id=' + model.get( 'id' ) + ']' ),
				index = $tab.index();
			if ( this.tabsControls().find( '[data-m-id]:eq(' + ( index + 1 ) + ')' ).length ) {
				window.vc.frame_window.vc_iframe.setActiveTab( this.$tabs, ( index + 1 ) );
			} else if ( this.tabsControls().find( '[data-m-id]:eq(' + ( index - 1 ) + ')' ).length ) {
				window.vc.frame_window.vc_iframe.setActiveTab( this.$tabs, ( index - 1 ) );
			} else {
				window.vc.frame_window.vc_iframe.setActiveTab( this.$tabs, 0 );
			}
			$tab.remove();
		},
		clone: function ( e ) {
			_.each( window.vc.shortcodes.where({ parent_id: this.model.get( 'id' ) }), function ( model ) {
				model.set( 'active_before_cloned', this.active_model_id === model.get( 'id' ) );
			}, this );
			window.InlineShortcodeView_vc_tabs.__super__.clone.call( this, e );
		}
	});
})( window.jQuery );
