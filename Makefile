help:
	@echo "Usage make <upload-to-beta|pot>"

upload-to-beta:
	rsync -avz --delete --delete-excluded --exclude='/node_modules/*' --exclude .git -e ssh ./ iwarcom@server.lichtmetzger.de:beta.i-war2.com/wp-content/themes/iwar-theme/

pot:
	./vendor/bin/wp i18n make-pot --domain=iwar-theme . languages/iwar-theme.pot