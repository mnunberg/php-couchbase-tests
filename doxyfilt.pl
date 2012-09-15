#!/usr/bin/perl
use strict;
use warnings;

my $fname = $ARGV[0] or die "Must have filename";
open my $fh, "<", $fname or die "$fname: $!";

my $re = qr/\@test_plans\{([^}]+)\}/;

while ( (my $line = <$fh> )) {

    $line =~ s/$re/\@xrefitem testplans "Test Plans" "Test Plans" $1/g;
    if ($1) {
        print STDERR "Replacing test_plan macro..\n";
    }
    print $line;
    
}
