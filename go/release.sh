date=`date +%y.%m.%d.%H%M%S`
cd /dragon/webapp/theone
git checkout dev
rm -rf assets/*
git pull origin dev
git checkout release
git merge --no-ff dev -m "release $date"
git push origin release