# Church live streaming

This setup uses MediaMTX for RTMP ingest and HLS playback. Laravel authenticates each encoder with the church's generated key and updates the live state when MediaMTX reports that a feed has started or stopped.

## Local setup

1. In the project `.env`, configure:

   ```dotenv
   STREAM_INGEST_URL=rtmp://127.0.0.1:1935
   STREAM_PLAYBACK_URL=http://127.0.0.1:8888/{path}/index.m3u8
   STREAM_PATH_PREFIX=live
   STREAM_AUTH_USERNAME_PREFIX=igm-church-
   STREAM_WEBHOOK_TOKEN=<long-random-secret>
   STREAM_AUTH_URL=http://host.docker.internal:8000/api/streaming/auth
   STREAM_WEBHOOK_URL=http://host.docker.internal:8000/api/streaming/status
   ```

   `STREAM_WEBHOOK_TOKEN` must be identical for Laravel and the media server. The app's HTTP server must accept connections from Docker. For Laravel's development server, run `php artisan serve --host=0.0.0.0` and keep port 8000 reachable from Docker Desktop.

2. On Windows, start MediaMTX from the project root. The launcher downloads the official release archive, checks its pinned SHA-256, and starts the server:

   ```powershell
   .\streaming\start-windows.ps1
   ```

   Leave this PowerShell window open while testing. Press Ctrl+C to stop the server.

   For Docker deployments, use:

   ```powershell
   docker compose -f docker-compose.streaming.yml up -d --build
   ```

3. Restart Laravel or clear its cached configuration with `php artisan config:clear`.

4. In Admin → Home hero → Broadcast setup, generate a key. Choose **Church RTMP server** in the live service settings and save.

5. In OBS, choose a custom RTMP service. Use the displayed server URL, enable **Use authentication**, use the displayed encoder username, and paste the private stream key as the password. Use H.264 video and AAC audio for broad browser playback compatibility.

When OBS connects, MediaMTX authenticates it with Laravel, converts the incoming RTMP feed to HLS, and calls Laravel's status endpoint. The home and sermon pages then show the live player automatically. Disconnecting OBS marks the stream offline and restores the hero banners. The public HLS address uses a separate, stable church path and does not include the private encoder key.

## Public deployment

Run MediaMTX on a public host reachable from the encoder. Set `STREAM_INGEST_URL` to that host's RTMP base URL and `STREAM_PLAYBACK_URL` to its public HTTPS HLS address, for example `https://media.example.org/{path}/index.m3u8`. Set `STREAM_AUTH_URL` and `STREAM_WEBHOOK_URL` to the public Laravel HTTPS endpoints. Terminate TLS for HLS with a reverse proxy and protect the Laravel app and media server with normal production firewall rules. The RTMP ingest port (1935/TCP) and HLS proxy must be reachable from their respective clients.

## Key handling

The generated key is encrypted in the database and used as the RTMP authentication password. Rotating it immediately rejects the old encoder credential. Public viewers can read HLS only while Laravel marks the church stream online. The media server's HTTP auth integration is fail-closed if Laravel is unavailable.
