cd /dragon/webapp/theone
git checkout boss
rm -rf assets/*
git pull origin boss
lastCommit=$(git log -1 --pretty=format:"%cn")
git checkout dev
git merge --no-ff boss -m "merge boss for dev LC $lastCommit"
git push origin dev