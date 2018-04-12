<template>
	<div class="section activity-section" :class="{ 'followup-section': sameDayAsSibling }">
		<h2 v-if="!sameDayAsSibling"><span class="has-tooltip" :title="dateOfDay">{{displayDate}}</span></h2>

		<div class="box" :data-activity-id="activity_id">
			<div class="messagecontainer">
				<div class="activity-icon">
					<img v-if="icon" :src="icon" alt="" />
				</div>
				<div class="activitysubject">
					<a :href="link" v-if="link" v-html="getSubject"></a>
					<template v-else v-html="getSubject"></template>
				</div>

				<span class="activitytime has-tooltip live-relative-timestamp" :data-timestamp="timestamp" :title="formatDate">
					{{relativeDate}}
				</span>

				<div class="activitymessage" v-if="getMessage" v-html="getMessage"></div>

				<div class="activity-previews" v-if="previews.length">
					<template  v-for="preview in previews">
						<a :href="preview.link" v-if="preview.link">
							<img class="preview" :class="{ 'preview-mimetype-icon' : preview.isMimeTypeIcon }" :src="preview.source" :alt="t('activity', 'Open file')" />
						</a>
						<template v-else><img class="preview" :class="{ 'preview-mimetype-icon' : preview.isMimeTypeIcon }" :src="preview.source" :alt="t('activity', 'Open file')" /></template>
					</template>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'activity',

		props: [
			'index',
			'activities',
			'activity_id',
			'icon',
			'link',
			'subject',
			'subject_rich',
			'message',
			'message_rich',
			'datetime',
			'previews'
		],

		computed: {
			timestamp: function () {
				return moment(this.datetime).valueOf();
			},
			formatDate: function () {
				return OC.Util.formatDate(this.timestamp);
			},
			relativeDate: function () {
				return OC.Util.relativeModifiedDate(this.timestamp);
			},
			dateOfDay: function () {
				return OC.Util.formatDate(this.timestamp, 'LL');
			},
			dayOfYear: function () {
				return OC.Util.formatDate(this.timestamp, 'YYYY-DDD');
			},
			sameDayAsSibling: function () {
				return this.index > 0 && this.dayOfYear === OC.Util.formatDate(moment(this.activities[this.index - 1].datetime).valueOf(), 'YYYY-DDD');
			},
			displayDate: function () {
				var displayDate = this.dateOfDay;

				if (this.dayOfYear === OC.Util.formatDate(moment(), 'YYYY-DDD')) {
					displayDate = t('activity', 'Today');
				} else {
					if (this.dayOfYear === OC.Util.formatDate(moment().subtract(1, 'd'), 'YYYY-DDD')) {
						displayDate = t('activity', 'Yesterday');
					}
				}

				return displayDate;
			},
			getSubject: function () {
				if (this.subject_rich[0].length > 1) {
					return OCA.Activity.RichObjectStringParser.parseMessage(this.subject_rich[0], this.subject_rich[1]);
				}
				return this.subject;
			},
			getMessage: function () {
				if (this.message_rich[0].length > 1) {
					return OCA.Activity.RichObjectStringParser.parseMessage(this.message_rich[0], this.message_rich[1]);
				}
				return this.message;
			},
			getLink: function () {
				if (this.getSubject().indexOf('<a') !== -1)
				return this.link;
			}
		},

		methods: {
			onClickActionButton: function () {
				$.ajax({
					url: this.link,
					type: this.type || 'GET',
					success: function () {
						this.$parent._$el.fadeOut(OC.menuSpeed);
						this.$parent.$emit('remove');
						$('body').trigger(new $.Event('OCA.Notification.Action', {
							notification: this.$parent,
							action: {
								url: this.link,
								type: this.type || 'GET'
							}
						}));
					}.bind(this),
					error: function () {
						OC.Notification.showTemporary(t('notifications', 'Failed to perform action'));
					}
				});
			}
		},

		mounted: function () {
			this._$el = $(this.$el);

			this._$el.find('.avatar').each(function() {
				var $avatar = $(this);
				if ($avatar.data('user-display-name')) {
					$avatar.avatar($avatar.data('user'), 21, undefined, false, undefined, $avatar.data('user-display-name'));
				} else {
					$avatar.avatar($avatar.data('user'), 21);
				}
			});

			this._$el.find('.avatar-name-wrapper').each(function() {
				var element = $(this),
					avatar = element.find('.avatar'),
					label = element.find('strong');

				$.merge(avatar, label).contactsMenu(element.data('user'), 0, element);
			});

			this._$el.find('.has-tooltip').tooltip({
				placement: 'bottom'
			});
		}
	}
</script>
