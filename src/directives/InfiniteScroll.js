/**
 * @copyright Copyright (c) 2021 Louis Chemineau <louis@chmn.me>
 *
 * @author Louis Chemineau <louis@chmn.me>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

let callback = null
let loading = false
let lastScrollTop = 0

async function handleScroll(scrollEvent) {
	if (callback === null || loading) {
		return
	}

	const scrollTop = scrollEvent.target.scrollTop
	const scrollHeight = scrollEvent.target.scrollHeight
	const clientHeight = scrollEvent.target.clientHeight
	const nearEndOfScroll = scrollTop >= scrollHeight - clientHeight - 500

	// Return here if the user is scrolling upward.
	if (scrollTop < lastScrollTop) {
		lastScrollTop = scrollTop
		return
	}
	lastScrollTop = scrollTop

	if (nearEndOfScroll) {
		try {
			console.warn(scrollTop, scrollHeight, clientHeight, nearEndOfScroll)
			loading = true
			await callback()
		} finally {
			loading = false
		}
	}
}

export default {
	inserted(el, binding) {
		callback = binding.value
		el.addEventListener('scroll', handleScroll)
	},
}
