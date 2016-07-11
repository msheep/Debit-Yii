cd /dragon/webapp/theone
git checkout www
rm -rf assets/*
git pull origin www
lastCommit=$(git log -1 --pretty=format:"%cn")
git checkout dev
git merge --no-ff www -m "merge www for dev LC $lastCommit"
git push origin dev