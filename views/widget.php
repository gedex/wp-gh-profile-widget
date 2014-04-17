<?php if ( ! empty( $profile['data'] ) ) : ?>

<?php $u = $profile['data']; // Shortcut. ?>
<div class="gh-profile-widget <?php echo esc_attr( $theme ); ?> vcard" itemscope="" itemtype="http://schema.org/Person">
	<a href="<?php echo esc_url( $u['html_url'] ); ?>" class="vcard-avatar" itemprop="image">
		<img class="avatar" data-user="<?php echo esc_attr( $u['id'] ); ?>" height="150" src="<?php echo esc_url( $u['avatar_url'] ); ?>" width="150">
	</a>

	<h1 class="vcard-names">
  	<span class="vcard-fullname" itemprop="name">
  		<a href="<?php echo esc_url( $u['html_url'] ); ?>"><?php echo esc_html( $u['name'] ) ?></a>
  	</span>
  	<span class="vcard-username" itemprop="additionalName">
  		<a href="<?php echo esc_url( $u['html_url'] ); ?>"><?php echo esc_html( $u['login'] ) ?></a>
  	</span>
	</h1>

	<ul class="vcard-details">
		<?php if ( ! empty( $u['company'] ) ) : ?>
		<li class="vcard-detail" itemprop="worksFor">
			<span class="octicon octicon-organization"></span>
			<?php echo esc_html( $u['company'] ) ?>
		</li>
		<?php endif; ?>

		<?php if ( ! empty( $u['location'] ) ) : ?>
		<li class="vcard-detail" itemprop="homeLocation">
			<span class="octicon octicon-location"></span>
			<?php echo esc_html( $u['location'] ) ?>
		</li>
		<?php endif; ?>

		<?php if ( ! empty( $u['email'] ) ) : ?>
		<li class="vcard-detail">
			<span class="octicon octicon-mail"></span>
			<a class="email" href="mailto:<?php echo esc_attr( $u['email'] ) ?>"><?php echo esc_html( $u['email'] ) ?></a>
		</li>
		<?php endif; ?>

		<?php if ( ! empty( $u['created_at'] ) ) : ?>
		<li class="vcard-detail">
			<span class="octicon octicon-clock"></span>
			<?php
				printf(
					__( '<span class="join-label">Joined on </span><span class="join-date">%s</span>', 'github-api' ),
					date_format( date_create( $u['created_at'] ), 'M d, Y' )
				);
			?>
		</li>
		<?php endif; ?>
	</ul>
	<!-- / vcard-details -->

	<div class="vcard-stats">
		<a class="vcard-stat" href="<?php echo esc_url( $u['followers_url'] ); ?>">
			<?php
			printf(
				__( '<strong class="vcard-stat-count">%s</strong> followers', 'github-api' ),

				$u['followers'] > 1000
				?
				round( $u['followers'] / 1000, 1 ) . 'k'
				:
				( $u['followers'] ? $u['followers'] : '0' )
			);
			?>
		</a>
		<a class="vcard-stat" href="<?php echo esc_url( $u['starred_url'] ); ?>">
			<?php
			printf(
				__( '<strong class="vcard-stat-count">%s</strong> starred', 'github-api' ),

				$u['starred'] > 1000
				?
				round( $u['starred'] / 1000, 1 ) . 'k'
				:
				( $u['starred'] ? $u['starred'] : '0' )
			);
			?>
		</a>
		<a class="vcard-stat" href="<?php echo esc_url( $u['following_url'] ); ?>">
			<?php
			printf(
				'<strong class="vcard-stat-count">%s</strong> following',

				$u['following'] > 1000
				?
				round( $u['following'] / 1000, 1 ) . 'k'
				:
				( $u['following'] ? $u['following'] : '0' )
			);
			?>
		</a>
	</div>
	<!-- / vcard-stats -->

	<?php if ( ! empty( $u['orgs'] ) ) : ?>
	<div class="vcard-orgs">
		<h3><?php _e( 'Organizations', 'github-api' ); ?></h3>
		<div class="avatars">
			<?php foreach ( $u['orgs'] as $o ) : ?>
			<a href="<?php echo esc_url( $o['url'] ); ?>" aria-label="<?php echo esc_attr( $o['login'] ); ?>" class="vcard-org-avatar" itemprop="follows">
				<img alt="<?php echo esc_attr( $o['login'] ); ?>" width="36" height="36" src="<?php echo esc_url( $o['avatar_url'] ); ?>" >
			</a>
			<?php endforeach; ?>
		</div>
	</div>
	<!-- / vcard-orgs -->
	<?php endif; ?>

</div>
<!-- / gh-profile-widget -->

<?php endif; ?>
