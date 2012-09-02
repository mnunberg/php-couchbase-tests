#!/usr/bin/perl
use strict;
use warnings;
use Getopt::Long;
use Cwd qw(getcwd);

GetOptions(
	"phpdir|s=s" => \my $SrcDir,
	"host|h=s" => \my $Host,
	"user|u=s" => \my $Username,
	"pass|p=s" => \my $Password,
	"bucket|b=s" => \my $Bucket);

$Bucket ||= "";
$Username ||= "";
$Password ||= "";

if (!$SrcDir) {
	die("Must have source directory!");
}

my $cwd = getcwd();

chdir $SrcDir or die "Couldn't chdir $SrcDir: $!";

if (!-d "$SrcDir/tests.orig") {
	system("mv tests tests.orig") == 0 
		or die "Couldn't save original test files";
}

system("cp -a $cwd/tests/ $SrcDir/tests") == 0 or die "Couldn't copy!";

if ($Host) {
	my $incfile = "$SrcDir/tests/couchbase.local.inc";
	open my $fh, ">", $incfile or die "$incfile: $!";

	print $fh <<"EOF";
<?php
define("COUCHBASE_CONFIG_HOST", "$Host");
define("COUCHBASE_CONFIG_USER", "$Username");
define("COUCHBASE_CONFIG_PASSWD", "$Password");
define("COUCHBASE_CONFIG_BUCKET", "$Bucket");
EOF
	close $fh
}
