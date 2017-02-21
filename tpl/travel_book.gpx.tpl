<?xml version="1.0" encoding="ISO-8859-1" standalone="no" ?>
<gpx creator="WTracks" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.topografix.com/GPX/1/1" version="1.1" xsi:schemaLocation="http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd">
<metadata>
  <name>Travel Book</name>
  <bounds minlat="{$bounds.minlat}" minlon="{$bounds.minlon}" maxlat="{$bounds.maxlat}" maxlon="{$bounds.maxlon}"/>
</metadata>
<wpt lat="48.853393" lon="2.348795">
<desc><![CDATA[Paris]]></desc>
</wpt>
{foreach from=$points item=point}
<wpt lat="{$point.lat}" lon="{$point.lon}">
<desc><![CDATA[{$point.title|utf8_decode}]]></desc>
</wpt>
{/foreach}
<rte>
<desc><![CDATA[Itinéraire]]></desc>
<rtept lat="48.853393" lon="2.348795">
<desc><![CDATA[Paris]]></desc>
</rtept>
{foreach from=$points item=point}
<rtept lat="{$point.lat}" lon="{$point.lon}">
<desc><![CDATA[{$point.title|utf8_decode}]]></desc>
</rtept>
{/foreach}
</rte>
</gpx>