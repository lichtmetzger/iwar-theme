help:
	@echo "Usage make <upload-to-beta>"

upload-to-beta:
	rsync -avz --delete --delete-excluded --exclude='/node_modules/*' --exclude .git -e ssh ./ iwarcom@server.lichtmetzger.de:beta.i-war2.com/wp-content/themes/iwar-theme/