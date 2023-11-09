<?php

namespace OCA\Theming {
	interface ThemingDefaults {
		public function getName(): string;
	
		public function getHTMLName(): string;

		public function getTitle(): string;
	
		public function getProductName(): string;
	
		public function getBaseUrl(): string;

		public function getSlogan(?string $lang = null): string;
	
		public function getImprintUrl(): string;
	
		public function getPrivacyUrl(): string;
	}
}
