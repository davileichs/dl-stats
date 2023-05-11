create index lastName ON hlstats_Players(lastName);

ALTER TABLE unloze_stats.hlstats_Events_Entries MODIFY COLUMN eventTime datetime  NULL;
create index map ON hlstats_Events_Entries(map);

ALTER TABLE unloze_stats.hlstats_Events_PlayerActions MODIFY COLUMN eventTime datetime  NULL;
create index map ON hlstats_Events_PlayerActions(map);
create index serverId ON hlstats_Events_PlayerActions(serverId);

ALTER TABLE unloze_stats.hlstats_Events_Statsme MODIFY COLUMN eventTime datetime  NULL;
create index serverId ON hlstats_Events_Statsme(serverId);
create index map ON hlstats_Events_Statsme(map);

ALTER TABLE unloze_stats.hlstats_Events_Statsme2 MODIFY COLUMN eventTime datetime  NULL;
create index map ON hlstats_Events_Statsme2(map);
create index serverId ON hlstats_Events_Statsme2(serverId);

create index server_id ON hlstats_Livestats(server_id);

create index map ON hlstats_server_load(map);
