help:
	@echo "Usage make <upload-to-beta>"

upload-to-beta:
	knock && rsync -avz --delete --delete-excluded --include=/node_modules/bootstrap/ --exclude='/node_modules/*' --exclude '/includes/*/*/node_modules' --exclude .git -e ssh ./ iwarcom@server.lichtmetzger.de:beta.i-war2.com/wp-content/themes/iwar-theme/