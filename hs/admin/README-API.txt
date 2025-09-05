
Test if there is an update for the given local version number:
/api.php?isUpdateForVersion=2

Response:
If no updates null, else:
	[sample response]  6&&2010-11-29 00:00:00&&unreleased version
	[format]  id&&date&&comments

Get the data for any given local update - gets all data for all more recent updates:
/api.php?getUpdatesForVersion=2

Response:
If no updates null, else:

Pipe delimited objects with && delimited values

Categories:
example:
	cat&&8&&80&&Stable Management&&fences, maint|cat&&10&&11&&new cat2&&test cat
format:
	[cat]&&id&&seqno&&name&&desc


Words:
example:
	word&&26&&5&&leg&&&&|word&&27&&11&&new word&&dasf&&|word&&28&&12&&test&&xxx&&dxs
format:
	[word]&&id&&catID&&english&&spanish&&literal


