FROM bluenviron/mediamtx:1.21.2 AS mediamtx

FROM alpine:3.22
RUN apk add --no-cache ca-certificates curl
COPY --from=mediamtx /mediamtx /mediamtx
COPY mediamtx.yml /etc/mediamtx.yml
ENTRYPOINT ["/mediamtx"]
CMD ["/etc/mediamtx.yml"]
